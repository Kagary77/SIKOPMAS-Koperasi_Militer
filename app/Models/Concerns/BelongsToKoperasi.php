<?php

namespace App\Models\Concerns;

use App\Support\Tenancy\KoperasiContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait untuk model tenant-owned (Product, Order, Sale, dll).
 * 1. Menambahkan where koperasi_id = ... secara otomatis pada semua query.
 * 2. Mengisi koperasi_id secara otomatis saat create (jika context tersedia).
 */
trait BelongsToKoperasi
{
    public static function bootBelongsToKoperasi(): void
    {
        // Global Scope: Koperasi A hanya bisa lihat data Koperasi A
        static::addGlobalScope('koperasi', function (Builder $builder): void {
            $context = app(KoperasiContext::class);
            if ($context->has()) {
                $builder->where($builder->qualifyColumn('koperasi_id'), $context->id());
            }
        });

        // Auto-fill: Saat create, otomatis isi koperasi_id dari context
        static::creating(function (Model $model): void {
            $context = app(KoperasiContext::class);
            if ($context->has() && empty($model->getAttribute('koperasi_id'))) {
                $model->setAttribute('koperasi_id', $context->id());
            }
        });
    }
}