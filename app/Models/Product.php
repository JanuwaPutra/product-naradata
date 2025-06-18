<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock'
    ];
    
    /**
     * Get the sale details for the product.
     */
    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
    
    /**
     * Get the sales for the product through sale details.
     */
    public function sales()
    {
        return $this->hasManyThrough(
            Sale::class,
            SaleDetail::class,
            'product_id', // Foreign key on SaleDetail table
            'id', // Foreign key on Sale table (primary key)
            'id', // Local key on Product table (primary key)
            'sale_id' // Local key on SaleDetail table
        );
    }
}
