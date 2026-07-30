<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(
        path: '/products',
        summary: 'List products',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Full-text search across title and content, ranked by relevance', schema: new OA\Schema(type: 'string', maxLength: 255)),
            new OA\Parameter(name: 'price_min', in: 'query', description: 'Minimum current price', schema: new OA\Schema(type: 'number', format: 'float', minimum: 0)),
            new OA\Parameter(name: 'price_max', in: 'query', description: 'Maximum current price, must be >= price_min', schema: new OA\Schema(type: 'number', format: 'float', minimum: 0)),
            new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', minimum: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of products',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Product')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/PaginationLinks', type: 'object'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta', type: 'object'),
                    ],
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error (e.g. price_max less than price_min)',
            ),
        ],
    )]
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $search = $request->validated('search');
        $priceMin = $request->validated('price_min');
        $priceMax = $request->validated('price_max');

        return ProductResource::collection(
            Product::query()
                ->when($search, fn (Builder $query, string $search) => $query
                    ->selectRaw('products.*, MATCH(title, content) AGAINST (?) as relevance', [$search])
                    ->whereFullText(['title', 'content'], $search)
                    ->orderByDesc('relevance')
                )
                ->when($priceMin !== null || $priceMax !== null, fn (Builder $query) => $query->whereHas(
                    'currentPrice',
                    fn (Builder $query) => $query
                        ->when($priceMin !== null, fn (Builder $query) => $query->where('price', '>=', $priceMin))
                        ->when($priceMax !== null, fn (Builder $query) => $query->where('price', '<=', $priceMax))
                ))
                ->with([
                    'category',
                    'currentPrice',
                    'defaultImage',
                    'images' => fn (HasMany $query) => $query->orderBy('sort_order'),
                ])
                ->paginate()
        );
    }
}
