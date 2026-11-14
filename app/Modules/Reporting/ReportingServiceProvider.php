<?php

namespace App\Modules\Reporting;

use App\Modules\ModuleServiceProvider;

final class ReportingServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak (interface) ditaruh di sini nanti
    }

    protected function moduleAlias(): string
    {
        return 'reporting'; // Sesuaikan dengan nama modul huruf kecil
    }
}