<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'products';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'products_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The "type" of the auto-incrementing ID.
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'categories_id',
        'price',
        'stock',
        'min_stock',
        'unit',
        'barcode',
        'image_url',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price' => 'decimal:0',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id', 'categories_id');
    }

    /**
     * Get the transaction details for the product.
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'products_id', 'products_id');
    }

    /**
     * Get the stock history for the product.
     */
    public function stockHistory()
    {
        return $this->hasMany(StockHistory::class, 'products_id', 'products_id');
    }

    /**
     * Check if stock is low.
     */
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Get stock status.
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock <= $this->min_stock) {
            return 'danger';
        } elseif ($this->stock <= $this->min_stock * 2) {
            return 'warning';
        }
        return 'normal';
    }
}
