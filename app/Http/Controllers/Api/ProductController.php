<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $search = $request->validated('search');
        $priceMin = $request->validated('price_min');
        $priceMax = $request->validated('price_max');

        return ProductResource::collection(
            Product::query()
                ->when($search, fn (Builder $query, string $search) => $query->where(
                    fn (Builder $query) => $query->where('title', 'like', "%{$search}%")
                        ->orWhereFullText('content', $search)
                ))
                ->when($priceMin !== null || $priceMax !== null, fn (Builder $query) => $query->whereHas(
                    'currentPrice',
                    fn (Builder $query) => $query
                        ->when($priceMin !== null, fn (Builder $query) => $query->where('price', '>=', $priceMin))
                        ->when($priceMax !== null, fn (Builder $query) => $query->where('price', '<=', $priceMax))
                ))
                ->with(['category', 'currentPrice'])
                ->paginate()
        );
    }
}
