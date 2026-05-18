<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'transactions';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'transactions_id';

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
        'users_id',
        'total_amount',
        'payment_method',
        'amount_paid',
        'change_amount',
        'transaction_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'total_amount' => 'decimal:0',
        'amount_paid' => 'decimal:0',
        'change_amount' => 'decimal:0',
        'transaction_date' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }

    /**
     * Get the transaction details.
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transactions_id', 'transactions_id');
    }
}
