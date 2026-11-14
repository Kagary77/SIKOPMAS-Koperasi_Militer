<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    protected $fillable = [
        'koperasi_id', 'customer_id', 'order_id', 
        'subject', 'description', 'attachment_path', 'status', 'admin_reply'
    ];

    public function koperasi(): BelongsTo { return $this->belongsTo(Koperasi::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}