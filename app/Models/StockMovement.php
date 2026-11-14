<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    public $timestamps = false; // Menonaktifkan created_at & updated_at bawaan
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'koperasi_id', 'product_id', 'user_id', 'type', 
        'qty', 'stock_before', 'stock_after', 'reference_id', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'stock_before' => 'integer',
            'stock_after' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function koperasi(): BelongsTo { return $this->belongsTo(Koperasi::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}