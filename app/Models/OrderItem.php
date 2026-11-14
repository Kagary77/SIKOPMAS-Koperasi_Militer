<?php

namespace App\Models;

use App\Models\Concerns\BelongsToKoperasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use BelongsToKoperasi;

    protected $fillable = [
        'koperasi_id', 'order_id', 'product_id', 'product_name_snapshot',
        'price_snapshot', 'qty', 'subtotal'
    ];

    protected function casts(): array
    {
        return [
            'price_snapshot' => 'integer',
            'qty' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function koperasi(): BelongsTo
    {
        return $this->belongsTo(Koperasi::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}