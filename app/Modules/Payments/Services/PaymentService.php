<?php

namespace App\Modules\Payments\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Modules\Payments\Contracts\PaymentGateway;
use App\Modules\Payments\Data\PaymentChargeRequest;
use Illuminate\Support\Facades\DB;

final class PaymentService
{
    public function __construct(private PaymentGateway $gateway) {}

    /**
     * Membuat tagihan QRIS jika belum ada.
     */
    public function initiate(Order $order): Payment
    {
        // Idempoten: Jika sudah ada payment, kembalikan yang lama
        $existing = Payment::where('order_id', $order->id)->first();
        if ($existing) {
            return $existing;
        }

        if ($order->status !== 'pending_payment') {
            throw new \Exception('Pesanan tidak dalam status menunggu pembayaran.');
        }

        return DB::transaction(function () use ($order) {
            // 1. Panggil Gateway (FakeQrisGateway dulu)
            $charge = $this->gateway->createQrisCharge(new PaymentChargeRequest(
                reference: 'PAY-' . $order->order_number,
                orderNumber: $order->order_number,
                amount: (int) $order->total_amount,
            ));

            // 2. Simpan ke tabel payments
            $payment = Payment::create([
                'koperasi_id' => $order->koperasi_id,
                'order_id' => $order->id,
                'payment_gateway_reference' => $charge->providerReference,
                'amount' => $order->total_amount,
                'status' => 'pending',
                'qr_url' => $charge->qrisPayload, // Di Midtrans asli ini URL gambar
                'paid_at' => null,
            ]);

            return $payment;
        });
    }

    /**
     * Simulasi Webhook (Khusus Sandbox/Testing)
     */
    public function simulatePayment(Payment $payment): void
    {
        if (! $this->gateway->isSandbox()) {
            throw new \Exception('Hanya tersedia di mode sandbox.');
        }

        DB::transaction(function () use ($payment) {
            // Kunci baris payment & order (ACID)
            $lockedPayment = Payment::lockForUpdate()->find($payment->id);
            $lockedOrder = Order::lockForUpdate()->find($payment->order_id);

            if ($lockedPayment->status === 'settled') {
                return; // Idempoten, jika sudah lunas jangan diproses lagi
            }

            // 1. Update Payment
            $lockedPayment->update([
                'status' => 'settled',
                'paid_at' => now(),
            ]);

            // 2. Update Order
            $lockedOrder->update([
                'status' => 'paid',
            ]);

            // 3. Catat Event (Append-Only)
            PaymentEvent::create([
                'koperasi_id' => $lockedPayment->koperasi_id,
                'payment_id' => $lockedPayment->id,
                'provider_event_id' => 'SIMULATED-' . uniqid(),
                'payload' => ['status' => 'settled', 'simulated' => true],
            ]);
        });
    }
}