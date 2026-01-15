<?php

namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class FilterableQueryDT
{
    protected Builder $builder;
    protected array $allowedFilters = [];
    protected array $allowedSearch = [];
    protected array $allowedRelationSearch = [];
    protected array $allowedSorts = [];
    protected array $allowedRelationSorts = [];
    protected array $joinedTables = []; // for de-duplication

    public static function for(Builder $builder): self
    {
        $instance = new self;
        $instance->builder = $builder;
        return $instance;
    }

    public function allowedFilters(array $filters): self
    {
        $this->allowedFilters = $filters;
        return $this;
    }

    public function allowedSearch(array $fields): self
    {
        $this->allowedSearch = $fields;
        return $this;
    }

    public function allowedRelationSearch(array $relations): self
    {
        $this->allowedRelationSearch = $relations;
        return $this;
    }

    public function allowedSorts(array $fields): self
    {
        $this->allowedSorts = $fields;
        return $this;
    }

    public function allowedRelationSorts(array $fields): self
    {
        $this->allowedRelationSorts = $fields;
        return $this;
    }

    /**
     * ------------------------------------------
     * GLOBAL SEARCH HANDLING
     * ------------------------------------------
     */
    public function apply(?string $searchValue = null): self
    {
        if (!$searchValue) {
            return $this;
        }

        $this->builder->where(function ($q) use ($searchValue) {

            // 1 Normal fields
            foreach ($this->allowedSearch as $field) {
                $q->orWhere($field, 'like', "%$searchValue%");
            }

            // 2 Relation fields
            foreach ($this->allowedRelationSearch as $relationName => $columns) {

                $q->orWhereHas($relationName, function ($qr) use ($columns, $searchValue) {

                    // MorphToMany special handling
                    if ($qr instanceof MorphToMany) {
                        $pivotTable = $qr->getTable();
                        $morphType = $qr->getMorphType();
                        $morphClass = $qr->getModel()->getMorphClass();

                        $qr->where(function ($sub) use ($columns, $searchValue, $pivotTable, $morphType, $morphClass) {
                            $sub->where("$pivotTable.$morphType", $morphClass);

                            foreach ($columns as $col) {
                                $sub->orWhere($col, 'like', "%$searchValue%");
                            }
                        });
                    } else {
                        // normal relations
                        $qr->where(function ($sub) use ($columns, $searchValue) {
                            foreach ($columns as $col) {
                                $sub->orWhere($col, 'like', "%$searchValue%");
                            }
                        });
                    }

                });

            }

        });

        return $this;
    }



    public function apply2(?string $searchValue = null): self
    {
        if ($searchValue) {
            $this->builder->where(function ($q) use ($searchValue) {
                foreach ($this->allowedSearch as $field) {
                    $q->orWhere($field, 'like', "%$searchValue%");
                }

                foreach ($this->allowedRelationSearch as $relation => $columns) {
                    $q->orWhereHas($relation, function ($qr) use ($columns, $searchValue, $relation) {
                        foreach ($columns as $col) {
                            $qr->orWhere($col, 'like', "%$searchValue%");
                        }

                        // special case for MorphToMany
                        $rel = $this->builder->getModel()->{$relation}();
                        if ($rel instanceof MorphToMany) {
                            $pivotTable = $rel->getTable();
                            $morphType = $rel->getMorphType();
                            $morphClass = $this->builder->getModel()->getMorphClass();

                            $qr->where("$pivotTable.$morphType", $morphClass);
                        }
                    });
                }
            });
        }

        return $this;
    }

    /**
     * ------------------------------------------
     * SORTING HANDLER (NORMAL + RELATIONAL)
     * ------------------------------------------
     */
    public function applySort(?string $sortColumn, string $direction = 'asc'): self
    {
        if (!$sortColumn) return $this;

        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        // Handle normal sorts
        if (in_array($sortColumn, $this->allowedSorts)) {
            $this->builder->orderBy($sortColumn, $direction);
            return $this;
        }

        // Handle relational sorts (e.g., roles.name)
        if (in_array($sortColumn, $this->allowedRelationSorts) && Str::contains($sortColumn, '.')) {
             $this->applyRelationSort($sortColumn, $direction);
             return $this;
        }
        return $this;
    }

    /**
     * ------------------------------------------
     * APPLY RELATION SORT
     * ------------------------------------------
     */
    protected function applyRelationSort(string $sortColumn, string $direction): void
    {
        [$relationName, $column] = explode('.', $sortColumn);
        $model = $this->builder->getModel();

        if (!method_exists($model, $relationName)) return;

        /** @var Relation $relation */
        $relation = $model->{$relationName}();
        $relationType = class_basename($relation);
        $alias = $relationName . '_sort_' . $column;


        // prevent duplicate joins
        if (in_array($alias, $this->joinedTables)) return;
        $this->joinedTables[] = $alias;

        /**
         * ----------------------------------------------------------
         * STANDARD RELATIONS
         * ----------------------------------------------------------
         */
        switch ($relationType) {

            case 'BelongsTo':
                $this->sortBelongsTo($relation, $model, $alias, $column, $direction);
                return;

            case 'HasOne':
            case 'HasMany':
                $this->sortHas($relation, $model, $alias, $column, $direction);
                return;

            case 'BelongsToMany':
                $this->sortBelongsToMany($relation, $model, $alias, $column, $direction);
                return;

            /**
             * ----------------------------------------------------------
             * MORPH RELATIONS
             * ----------------------------------------------------------
             */

            case 'MorphOne':
                $this->sortMorphOne($relation, $model, $alias, $column, $direction);
                return;

            case 'MorphMany':
                $this->sortMorphMany($relation, $model, $alias, $column, $direction);
                return;

            case 'MorphToMany':
                $this->sortMorphToMany($relation, $model, $alias, $column, $direction);
                return;

            case 'MorphTo':
                // Cannot directly sort because morphTo has no single table
                // We can support it only if morph types are known — skipping
                return;
        }
    }

    /**
     * ----------------------------------------------------------
     * SORT HANDLERS
     * ----------------------------------------------------------
     */

    protected function sortBelongsTo($relation, $model, $alias, $column, $direction)
    {
        $table = $relation->getRelated()->getTable();
        $foreignKey = $relation->getForeignKeyName();
        $ownerKey = $relation->getOwnerKeyName();

        $this->builder
            ->leftJoin("$table as $alias", $model->getTable().'.'.$foreignKey, '=', "$alias.$ownerKey")
            ->select($model->getTable().'.*')
            ->orderBy("$alias.$column", $direction);
    }

    protected function sortHas($relation, $model, $alias, $column, $direction)
    {
        $table = $relation->getRelated()->getTable();
        $localKey = $relation->getLocalKeyName();
        $foreignKey = $relation->getForeignKeyName();

        $this->builder
            ->leftJoin("$table as $alias", "$alias.$foreignKey", '=', $model->getTable().'.'.$localKey)
            ->select($model->getTable().'.*')
            ->orderBy("$alias.$column", $direction);
    }

    protected function sortBelongsToMany($relation, $model, $alias, $column, $direction)
    {
        $pivot = $relation->getTable();
        $pivotAlias = $alias . '_pivot';
        $relatedTable = $relation->getRelated()->getTable();

        $this->builder
            ->leftJoin("$pivot as $pivotAlias", $model->getTable().'.'.$relation->getParentKeyName(), '=', "$pivotAlias.".$relation->getForeignPivotKeyName())
            ->leftJoin("$relatedTable as $alias", "$pivotAlias.".$relation->getRelatedPivotKeyName(), '=', "$alias.".$relation->getRelatedKeyName())
            ->select($model->getTable().'.*')
            ->orderBy("$alias.$column", $direction);
    }


    protected function sortMorphOne(MorphOne $relation, $model, $alias, $column, $direction)
    {
        $table = $relation->getRelated()->getTable();
        $foreignKey = $relation->getForeignKeyName();
        $localKey = $relation->getLocalKeyName();
        $morphType = $relation->getMorphType();
        $morphClass = $relation->getMorphClass();

        $this->builder
            ->leftJoin("$table as $alias", function ($join) use ($alias, $foreignKey, $model, $localKey, $morphType, $morphClass) {
                $join->on("$alias.$foreignKey", '=', $model->getTable().'.'.$localKey)
                     ->where("$alias.$morphType", '=', $morphClass);
            })
            ->select($model->getTable().'.*')
            ->orderBy("$alias.$column", $direction);
    }

    protected function sortMorphMany(MorphMany $relation, $model, $alias, $column, $direction)
    {
        $this->sortMorphOne($relation, $model, $alias, $column, $direction);
    }


    protected function sortMorphToMany(MorphToMany $relation, $model, $alias, $column, $direction)
    {
        $pivot = $relation->getTable();
        $relatedTable = $relation->getRelated()->getTable();
        $pivotAlias = $alias . '_pivot';

        $this->builder
            ->leftJoin("$pivot as $pivotAlias", function ($join) use ($pivotAlias, $relation, $model) {
                $join->on($pivotAlias.'.'.$relation->getForeignPivotKeyName(), '=', $model->getTable().'.'.$relation->getParentKeyName())
                     ->where($pivotAlias.'.'.$relation->getMorphType(), '=', $model->getMorphClass());
            })
            ->leftJoin("$relatedTable as $alias", "$pivotAlias.".$relation->getRelatedPivotKeyName(), '=', "$alias.".$relation->getRelatedKeyName())
            ->select($model->getTable().'.*')
            ->orderBy("$alias.$column", $direction);
    }

    public function getBuilder(): Builder
    {
        return $this->builder;
    }
}




    // protected function applyRelationSort(string $sortColumn, string $direction): void
    // {
    //     [$relationName, $column] = explode('.', $sortColumn);

    //     $model = $this->builder->getModel();

    //     if (!method_exists($model, $relationName)) {
    //         return; // invalid relation
    //     }

    //     /** @var Relation $relation */
    //     $relation = $model->{$relationName}();

    //     $relationType = class_basename($relation);

    //     $alias = $relationName . '_sort_' . $column;

    //     switch ($relationType) {

    //         case 'BelongsTo':
    //             $foreignKey = $relation->getForeignKeyName();
    //             $ownerKey = $relation->getOwnerKeyName();
    //             $table = $relation->getRelated()->getTable();

    //             $this->builder->leftJoin("$table as $alias", $model->getTable().'.'.$foreignKey, '=', "$alias.$ownerKey")
    //                 ->select($model->getTable().'.*')
    //                 ->orderBy("$alias.$column", $direction);

    //             break;

    //         case 'HasOne':
    //         case 'HasMany':
    //             $foreignKey = $relation->getForeignKeyName();
    //             $localKey = $relation->getLocalKeyName();
    //             $table = $relation->getRelated()->getTable();

    //             $this->builder->leftJoin("$table as $alias", "$alias.$foreignKey", '=', $model->getTable().'.'.$localKey)
    //                 ->select($model->getTable().'.*')
    //                 ->orderBy("$alias.$column", $direction);

    //             break;

    //         case 'BelongsToMany':
    //             $pivot = $relation->getTable();
    //             $relatedTable = $relation->getRelated()->getTable();
    //             $parentKey = $relation->getParentKeyName();
    //             $relatedKey = $relation->getRelatedKeyName();

    //             $pivotAlias = $alias . '_pivot';

    //             $this->builder
    //                 ->leftJoin("$pivot as $pivotAlias", $model->getTable().'.'.$parentKey, '=', "$pivotAlias.".$relation->getForeignPivotKeyName())
    //                 ->leftJoin("$relatedTable as $alias", "$pivotAlias.".$relation->getRelatedPivotKeyName(), '=', "$alias.$relatedKey")
    //                 ->select($model->getTable().'.*')
    //                 ->orderBy("$alias.$column", $direction);

    //             break;

    //         case 'MorphToMany':
    //             /** @var MorphToMany $relation */
    //             $pivot = $relation->getTable();
    //             $relatedTable = $relation->getRelated()->getTable();

    //             $pivotAlias = $alias . '_pivot';

    //             $this->builder
    //                 ->leftJoin("$pivot as $pivotAlias", function ($join) use ($pivotAlias, $relation, $model) {
    //                     $join->on($pivotAlias.'.'.$relation->getForeignPivotKeyName(), '=', $model->getTable().'.'.$relation->getParentKeyName())
    //                          ->where($pivotAlias.'.'.$relation->getMorphType(), '=', $model->getMorphClass());
    //                 })
    //                 ->leftJoin("$relatedTable as $alias", "$pivotAlias.".$relation->getRelatedPivotKeyName(), '=', "$alias.".$relation->getRelatedKeyName())
    //                 ->select($model->getTable().'.*')
    //                 ->orderBy("$alias.$column", $direction);

    //             break;


    //     }
    // }