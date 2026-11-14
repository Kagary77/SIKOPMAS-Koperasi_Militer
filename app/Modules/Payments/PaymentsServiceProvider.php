<?php

namespace App\Modules\Payments;

use App\Modules\ModuleServiceProvider;

final class PaymentsServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak (interface) ditaruh di sini nanti
    }

    protected function moduleAlias(): string
    {
        return 'payments'; // Sesuaikan dengan nama modul huruf kecil
    }
}