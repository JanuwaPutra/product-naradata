<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'quantity',
        'price_per_item',
        'total_price',
        'sale_date'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sale_date' => 'datetime',
    ];
    
    /**
     * Get the product that owns the sale.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
