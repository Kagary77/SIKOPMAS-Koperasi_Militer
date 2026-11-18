// Tambahkan import di atas
use App\Modules\Ordering\Services\CartService;
use Illuminate\Support\Facades\Auth;

// Di dalam class:
public function addToCart(int $productId): void
{
    if (! Auth::guard('customer')->check()) {
        $this->redirect(route('customer.login'), navigate: true);
        return;
    }

    $product = Product::where('koperasi_id', $this->koperasiId)->findOrFail($productId);
    
    if ($product->stock < 1) {
        session()->flash('error', 'Stok produk habis.');
        return;
    }

    app(CartService::class)->addProduct(
        Auth::guard('customer')->user(), 
        $this->koperasiId, 
        $product
    );

    $this->dispatch('cart-updated'); // Sinyal ke UI lain
    session()->flash('success', 'Produk ditambahkan ke keranjang.');
}

<button 
    wire:click="addToCart({{ $product->id }})"
    wire:loading.attr="disabled"
    class="mt-2 w-full rounded-lg bg-red-600 py-2 text-sm font-bold text-white transition hover:bg-red-700"
>
    + Keranjang
</button>