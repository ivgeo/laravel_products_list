<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $full_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
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
 * @property-read \App\Models\CategoryProduct|null $productCount
 * @mixin \Eloquent
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $category_id
 * @property int $products_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct whereProductsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class CategoryProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property Carbon $date
 * @property int $product_id
 * @property numeric $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceList whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PriceList extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property int $category_id
 * @property string|null $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Collection<int, PriceList> $priceLists
 * @property-read int|null $price_lists_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @property int|null $default_image_id
 * @property-read ProductPrice|null $currentPrice
 * @property-read ProductImage|null $defaultImage
 * @property-read Collection<int, ProductImage> $images
 * @property-read int|null $images_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDefaultImageId($value)
 * @mixin \Eloquent
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string $disk
 * @property string $path
 * @property string $original_filename
 * @property string $mime_type
 * @property int $size
 * @property int $width
 * @property int $height
 * @property string|null $alt_text
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereOriginalFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereWidth($value)
 * @mixin \Eloquent
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $product_id
 * @property numeric $price
 * @property \Illuminate\Support\Carbon $effective_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereUpdatedAt($value)
 */
	class ProductPrice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

