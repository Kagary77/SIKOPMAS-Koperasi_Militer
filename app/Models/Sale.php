<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'koperasi_id', 'shift_id', 'invoice_number', 'total_amount', 
        'payment_method', 'amount_paid', 'change_amount', 'status'
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'integer',
            'amount_paid' => 'integer',
            'change_amount' => 'integer',
        ];
    }

    public function koperasi(): BelongsTo { return $this->belongsTo(Koperasi::class); }
    public function shift(): BelongsTo { return $this->belongsTo(CashierShift::class); }
    public function items(): HasMany { return $this->hasMany(SaleItem::class); }
}