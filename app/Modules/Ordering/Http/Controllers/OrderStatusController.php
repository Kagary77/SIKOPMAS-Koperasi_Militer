<?php
namespace App\Modules\Ordering\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Koperasi;
use App\Models\Order;
use Illuminate\Contracts\View\View;
class OrderStatusController extends Controller
{
    public function __invoke(Koperasi $koperasi, string $orderNumber): View
    {
        // Pastikan order milik pelanggan yang login
        $order = Order::where('order_number', $orderNumber)
            ->where('customer_id', auth('customer')->id())
            ->where('koperasi_id', $koperasi->id)
            ->firstOrFail();

        return view('ordering::customer.order', compact('koperasi', 'order'));
    }
}