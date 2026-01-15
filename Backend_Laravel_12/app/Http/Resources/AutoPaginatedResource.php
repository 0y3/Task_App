<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Http\Resources\Json\JsonResource;

class AutoPaginatedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected $resourceClass;

    public function __construct($resource, string $resourceClass)
    {
        parent::__construct($resource);
        $this->resourceClass = $resourceClass;
    }
    
    public function toArray(Request $request): array
    {
    // CASE 1: It's a paginator → return full pagination structure
        if ($this->resource instanceof AbstractPaginator) {
            return [
                'data' => $this->resourceClass::collection($this->resource->items()),
                'meta' => [
                    'current_page' => $this->resource->currentPage(),
                    'per_page' => $this->resource->perPage(),
                    'total' => method_exists($this->resource, 'total') 
                        ? $this->resource->total() 
                        : null,
                    'last_page' => method_exists($this->resource, 'lastPage') 
                        ? $this->resource->lastPage() 
                        : null,
                    'from' => $this->resource->firstItem(),
                    'to' => $this->resource->lastItem(),
                ],
                'links' => [
                    'first' => $this->resource->url(1),
                    'last' => method_exists($this->resource, 'lastPage') 
                        ? $this->resource->url($this->resource->lastPage()) 
                        : null,
                    'prev' => $this->resource->previousPageUrl(),
                    'next' => $this->resource->nextPageUrl(),
                ],
            ];
        }

        // CASE 2: It's a collection → return array of items
        if (is_iterable($this->resource)) {
            return [
                'data' => $this->resourceClass::collection($this->resource),
            ];
        }

        // CASE 3: It's a single model → return one resource
        return (new $this->resourceClass($this->resource))->toArray($request);
    }
}
