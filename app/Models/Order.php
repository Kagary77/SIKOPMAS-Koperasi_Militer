<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['koperasi_id', 'customer_id', 'order_number', 'total_amount', 'status'];
    
    protected function casts(): array {
        return ['total_amount' => 'integer'];
    }

    public function koperasi(): BelongsTo { return $this->belongsTo(Koperasi::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}