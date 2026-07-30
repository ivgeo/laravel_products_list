<?php

namespace App\Models;

use App\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $full_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 *
 * @property-read CategoryProduct|null $productCount
 *
 * @mixin \Eloquent
 */
#[ObservedBy(CategoryObserver::class)]
class Category extends Model
{
    use HasFactory;

    /**
     * Separator used to join ancestor names into the full_name breadcrumb.
     */
    public const FULL_NAME_SEPARATOR = '\\';

    protected $fillable = [
        'code',
        'name',
        'full_name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productCount(): HasOne
    {
        return $this->hasOne(CategoryProduct::class);
    }

    /**
     * The code of the direct parent of $code, derived by dropping its last
     * dot-separated segment (e.g. "1.1.1." -> "1.1."), or null for a root code.
     */
    public static function parentCode(string $code): ?string
    {
        $segments = array_values(array_filter(explode('.', $code), fn (string $segment) => $segment !== ''));
        array_pop($segments);

        return $segments === [] ? null : implode('.', $segments).'.';
    }

    /**
     * Ancestor codes for $code, root-first, by walking parentCode() recursively.
     *
     * @return array<int, string>
     */
    public static function ancestorCodes(string $code): array
    {
        $parentCode = self::parentCode($code);

        if ($parentCode === null) {
            return [];
        }

        return [...self::ancestorCodes($parentCode), $parentCode];
    }
}
