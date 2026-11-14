<?php

namespace App\Modules\Catalog;

use App\Modules\ModuleServiceProvider;

final class CatalogServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak (interface) ditaruh di sini nanti
    }

    protected function moduleAlias(): string
    {
        return 'catalog'; // Sesuaikan dengan nama modul huruf kecil
    }
}