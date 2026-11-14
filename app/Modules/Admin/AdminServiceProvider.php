<?php

namespace App\Modules\Admin;

use App\Modules\ModuleServiceProvider;

final class AdminServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak (interface) ditaruh di sini nanti
    }

    protected function moduleAlias(): string
    {
        return 'admin'; // Sesuaikan dengan nama modul huruf kecil
    }
}