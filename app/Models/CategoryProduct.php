<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $category_id
 * @property int $products_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct whereProductsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CategoryProduct extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'products_count',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
