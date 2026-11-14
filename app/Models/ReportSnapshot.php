<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSnapshot extends Model
{
    public $timestamps = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'koperasi_id', 'report_date', 'total_sales', 
        'total_transactions', 'total_products_sold', 'total_complaints'
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'total_sales' => 'integer',
            'total_transactions' => 'integer',
            'total_products_sold' => 'integer',
            'total_complaints' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function koperasi(): BelongsTo { return $this->belongsTo(Koperasi::class); }
}