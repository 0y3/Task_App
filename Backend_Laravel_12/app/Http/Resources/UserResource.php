<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            // "isAdmin" => $this->hasRole('admin'),
            "roles" => $this->when(
                true,
                function () {
                    if ($this->relationLoaded('roles')) {
                        // Use eager-loaded relation (no extra SQL query)
                        return $this->roles->pluck('name');
                    }

                    // Fallback to spatie permission (runs SQL only when needed)
                    return $this->getRoleNames();
                }
            ),
            // "permissions" => $this->when(
            //     true,
            //     function () {
            //         if ($this->relationLoaded('permissions')) {
            //             return $this->permissions->pluck('name');
            //         }

            //         // Includes role permissions + direct permissions
            //         return $this->getAllPermissions()->pluck('name');
            //     }
            // ),
            /**
             * ADVANCED PERMISSIONS
             */
            "permissions" => $this->when(
                true,
                function () {
                    // $allPermissions = $this->relationLoaded('permissions')
                    //     ? $this->permissions
                    //     : $this->getAllPermissions();

                    // Group permissions by module/feature if dot notation is used
                    // $grouped = [];
                    // foreach ($allPermissions as $perm) {
                    //     $name = $perm->name;
                    //     if (str_contains($name, '.')) {
                    //         [$module, $action] = explode('.', $name, 2);
                    //         $grouped[$module][] = $action;
                    //     } else {
                    //         $grouped['general'][] = $name;
                    //     }
                    // }

                    // // Include counts
                    // return [
                    //     'total' => $allPermissions->count(),
                    //     'by_module' => $grouped,
                    // ];

                    $allPermissions = $this->getAllPermissions();
                    return $allPermissions->groupBy(function ($permission) {
                        return str_contains($permission->name, '.')
                            ? explode('.', $permission->name, 2)[0]
                            : 'general';
                    })->map(function ($permissions) {
                        return $permissions->map(function ($permission) {
                            return str_contains($permission->name, '.')
                                ? explode('.', $permission->name, 2)[1]
                                : $permission->name;
                        });
                    });
                }
            ),
            'counts' => [
                'roles' => $this->relationLoaded('roles') ? $this->roles->count() : $this->getRoleNames()->count(),
                'permissions' => $this->getAllPermissions()->count(),
            ],
            // 'roles' => $this->getRoleNames()
            // performance optimizations
            // 'roles' => $this->whenLoaded('roles', fn() => $this->roles->pluck('name')),

        ];
        // return parent::toArray($request);
    }
}
