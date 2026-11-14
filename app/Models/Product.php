<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['koperasi_id', 'category_id', 'name', 'sku', 'barcode', 'price', 'stock', 'is_active'];
    
    protected function casts(): array {
        return ['price' => 'integer', 'stock' => 'integer', 'is_active' => 'boolean'];
    }

    public function koperasi(): BelongsTo { return $this->belongsTo(Koperasi::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
}