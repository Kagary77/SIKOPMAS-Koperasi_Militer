<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'koperasi_id', 'order_id', 'payment_gateway_reference', 
        'amount', 'status', 'qr_url', 'paid_at'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function koperasi(): BelongsTo { return $this->belongsTo(Koperasi::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function events(): HasMany { return $this->hasMany(PaymentEvent::class); }
}