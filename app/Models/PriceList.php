<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon $date
 * @property int $product_id
 * @property numeric $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PriceList extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'date',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
