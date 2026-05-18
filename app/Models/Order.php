<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'items',
        'total_amount',
        'status',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'items' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber()
    {
        $lastOrder = self::latest()->first();
        $lastNumber = $lastOrder ? intval(substr($lastOrder->order_number, 3)) : 0;
        return 'ORD' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    }
}
