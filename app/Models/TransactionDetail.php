<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'transaction_details';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'transaction_details_id';

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
        'transactions_id',
        'products_id',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:0',
        'subtotal' => 'decimal:0',
    ];

    /**
     * Get the transaction that owns the detail.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id', 'transactions_id');
    }

    /**
     * Get the product that was sold.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'products_id');
    }
}
