<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilterableQuery
{
    protected Builder $query;
    protected Request $request;

    protected array $allowedSorts = [];
    protected array $allowedRelationSorts = [];

    protected array $allowedFilters = [];
    protected array $allowedSearch = [];
    protected array $allowedRelationSearch = [];

    public static function for(Builder $query)
    {
        return new static($query);
    }

    public function __construct(Builder $query)
    {
        $this->query = $query;
        $this->request = request();
    }


    /****************************************
    *            ALLOWED METHODS
    *****************************************/

    public function allowedSorts(array $sorts)
    {
        $this->allowedSorts = $sorts;
        return $this;
    }

    public function allowedRelationSorts(array $relations)
    {
        $this->allowedRelationSorts = $relations;
        return $this;
    }

    public function allowedFilters(array $filters)
    {
        $this->allowedFilters = $filters;
        return $this;
    }

    public function allowedSearch(array $columns)
    {
        $this->allowedSearch = $columns;
        return $this;
    }

    public function allowedRelationSearch(array $relations)
    {
        $this->allowedRelationSearch = $relations;
        return $this;
    }


    /****************************************
    *            APPLY ALL RULES
    *****************************************/

    public function apply(): Builder
    {
        return $this->applyFilters()
                    ->applySearch()
                    ->applyRelationSearch()
                    ->applySorting()
                    ->applyRelationSorting()
                    ->query;
    }


    /****************************************
    *               FILTERING
    *****************************************/

    protected function applyFilters()
    {
        foreach ($this->allowedFilters as $filter) {
            if ($this->request->filled($filter)) {
                $this->query->where($filter, $this->request->get($filter));
            }
        }

        // Relationship filter (roles, etc.)
        if ($this->request->filled('role')) {
            $this->query->whereHas('roles', function ($q) {
                $q->where('name', $this->request->role);
            });
        }

        return $this;
    }


    /****************************************
    *         SEARCH (Model Columns)
    *****************************************/

    protected function applySearch()
    {
        if (!$this->request->filled('search')) {
            return $this;
        }

        $search = $this->request->search;

        $this->query->where(function ($q) use ($search) {
            foreach ($this->allowedSearch as $column) {
                $q->orWhere($column, 'LIKE', "%{$search}%");
            }
        });

        return $this;
    }


    /****************************************
    *         SEARCH (Relation Columns)
    *****************************************/

    protected function applyRelationSearch()
    {
        if (!$this->request->filled('search')) {
            return $this;
        }

        $search = $this->request->search;

        foreach ($this->allowedRelationSearch as $relation => $columns) {
            $this->query->orWhereHas($relation, function ($q) use ($columns, $search) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            });
        }

        return $this;
    }


    /****************************************
    *          SORTING NORMAL COLUMNS
    *****************************************/

    protected function applySorting()
    {
        $sortBy = $this->request->get('sortBy');
        $sortDir = $this->request->get('sortDir', 'asc');

        if ($sortBy && in_array($sortBy, $this->allowedSorts)) {
            $dir = strtolower($sortDir) === 'desc' ? 'desc' : 'asc';
            $this->query->orderBy($sortBy, $dir);
        }

        return $this;
    }


    /****************************************
    *      FULLY DYNAMIC RELATION SORTING
    *   supports roles.name, company.country.name, etc.
    *****************************************/

    protected function applyRelationSorting()
    {
        $sortBy = $this->request->get('sortBy');
        $sortDir = $this->request->get('sortDir', 'asc');

        if (!$sortBy || !str_contains($sortBy, '.')) {
            return $this;
        }

        if (!in_array($sortBy, $this->allowedRelationSorts)) {
            return $this; // prevent SQL injection
        }

        $parts = explode('.', $sortBy);
        $column = array_pop($parts);
        $relations = $parts;

        $query = $this->query;
        $model = $query->getModel();
        $mainTable = $model->getTable();

        $parentTable = $mainTable;

        foreach ($relations as $relationName) {
            $relation = $model->{$relationName}();

            $relatedModel = $relation->getRelated();
            $relatedTable = $relatedModel->getTable();

            if (method_exists($relation, 'getQualifiedForeignKeyName')) {
                $foreignKey = $relation->getQualifiedForeignKeyName();
            } elseif (method_exists($relation, 'getForeignKeyName')) {
                $foreignKey = $parentTable.'.'.$relation->getForeignKeyName();
            } else {
                continue;
            }

            if (method_exists($relation, 'getQualifiedOwnerKeyName')) {
                $ownerKey = $relation->getQualifiedOwnerKeyName();
            } else {
                $ownerKey = $relatedTable.'.'.$relatedModel->getKeyName();
            }

            $query->leftJoin($relatedTable, $foreignKey, '=', $ownerKey);

            $model = $relatedModel;
            $parentTable = $relatedTable;
        }

        $query->orderBy($parentTable.'.'.$column, $sortDir);

        return $this;
    }
}
