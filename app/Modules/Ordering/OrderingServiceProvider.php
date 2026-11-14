<?php

namespace App\Modules\Ordering;

use App\Modules\ModuleServiceProvider;

final class OrderingServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak (interface) ditaruh di sini nanti
    }

    protected function moduleAlias(): string
    {
        return 'ordering'; // Sesuaikan dengan nama modul huruf kecil
    }
}