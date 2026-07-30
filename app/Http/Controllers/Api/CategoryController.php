<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    #[OA\Get(
        path: '/categories',
        summary: 'List categories',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', minimum: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of categories',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Category')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/PaginationLinks', type: 'object'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta', type: 'object'),
                    ],
                ),
            ),
        ],
    )]
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(
            Category::query()
                ->with('productCount')
                ->paginate()
        );
    }
}
