<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Support\FilterableQuery;
use App\Support\FilterableQueryDT;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\AutoPaginatedResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return UserResource::collection(User::simplePaginate(2));

        // examples
        // $data =  [
        //     'paginatedUsers' => new AutoPaginatedResource(User::paginate(2), UserResource::class),
        //     'simplePaginate' => new AutoPaginatedResource(User::simplePaginate(2), UserResource::class),
        //     'allUsers' => UserResource::collection(User::all()),
        //     'singleUser' => new AutoPaginatedResource(User::find(1), UserResource::class),
        // ];
        // return $data;

        // axios.get("/api/users", {
        //     params: {
        //         page: this.page,
        //         perPage: this.perPage,
        //         search: this.search,
        //         sortBy: this.sort.column,       // e.g., roles.name
        //         sortDir: this.sort.direction,   // asc/desc
        //         role: this.filters.role,        // filter by role
        //         module: this.filters.module     // optional: filter by permission module
        //     }
        // });

        $query = FilterableQuery::for(User::query())
            ->allowedFilters(['status'])
            ->allowedSearch(['name', 'email'])
            ->allowedRelationSearch([
                'roles' => ['name'],'permissions'=>['name'],
            ])
            ->allowedSorts(['name', 'email'])
            ->allowedRelationSorts([
                'roles.name','permissions.name',
                // 'company.name','company.country.name',
            ])
            ->apply();

        // Pagination from frontend (fallback to 30)
        $users = $query->paginate($request->perPage ?? 2);
        // $users = $query->find(2);
        return new AutoPaginatedResource($users, UserResource::class);
    }

    public function indexDataTable(Request $request)
    {
        $perPage = $request->input('length', 30);
        $page = floor($request->input('start', 0) / $perPage) + 1;
        $searchValue = $request->input('search.value');

        // 🔹 Sorting
        $orderColumnValue = $request->order[0]['column'];
        $orderDirection = $request->order[0]['dir'] ?? 'asc';

        // Column name sent by DataTables
        if (is_numeric($orderColumnValue)) {
            // If index → find matching request column name
            $sortColumn = $request->columns[$orderColumnValue]['data'];
        } else {
            // If name → use directly
            $sortColumn = $orderColumnValue;
        }

        $query = FilterableQueryDT::for(User::query())
            ->allowedFilters(['status'])
            ->allowedSearch(['name', 'email'])
            ->allowedRelationSearch([
                'roles' => ['name'],
                'permissions' => ['name'],
            ])
            ->allowedSorts(['name', 'email'])
            ->allowedRelationSorts([
                'roles.name',
                'permissions.name',
            ])
            ->apply($searchValue)
            ->applySort($sortColumn, $orderDirection)
            ->getBuilder();

        $query->with(['roles', 'permissions']);
        $users = $query->paginate($perPage, ['*'], 'page', $page);

        // ────────────────────────────────────────
        // DATATABLE RESPONSE FORMAT
        // ────────────────────────────────────────
        return [
            "draw" => intval($request->draw),
            "recordsTotal" => $users->total(),
            "recordsFiltered" => $users->total(),
            "data" => UserResource::collection($users),
        ];
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
