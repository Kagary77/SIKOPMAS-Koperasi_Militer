<?php

namespace App\Modules\Payments\Contracts;

interface PaymentGateway
{
    // Definisikan method yang wajib ada, contoh:
    public function createCharge(PaymentChargeRequest $request);
}