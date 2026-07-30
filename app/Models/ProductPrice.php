<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'price',
        'effective_date',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'effective_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
