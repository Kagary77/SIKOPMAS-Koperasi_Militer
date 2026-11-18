<?php
namespace App\Modules\Ordering\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Koperasi;
use Illuminate\Contracts\View\View;
class CustomerCheckoutController extends Controller
{
    public function __invoke(Koperasi $koperasi): View
    {
        return view('ordering::customer.checkout', compact('koperasi'));
    }
}