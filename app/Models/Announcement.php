<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = ['target_koperasi_id', 'title', 'content'];

    public function targetKoperasi(): BelongsTo
    {
        return $this->belongsTo(Koperasi::class, 'target_koperasi_id');
    }
}