<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'stock_history';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'stock_history_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The "type" of the auto-incrementing ID.
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'products_id',
        'change_type',
        'quantity_change',
        'stock_before',
        'stock_after',
        'reference_id',
        'note',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quantity_change' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the product that owns the history.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'products_id');
    }
}
