<?php

namespace App\Models;

use App\Models\Concerns\BelongsToKoperasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentEvent extends Model
{
    use BelongsToKoperasi;

    // Tabel append-only
    const UPDATED_AT = null;

    protected $fillable = [
        'koperasi_id', 'payment_id', 'provider_event_id', 'payload'
    ];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }

    public function koperasi(): BelongsTo
    {
        return $this->belongsTo(Koperasi::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}