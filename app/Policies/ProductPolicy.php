<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Support\Tenancy\KoperasiContext;

class ProductPolicy
{
    private function ownedByActiveKoperasi(Product $product): bool
    {
        return $product->koperasi_id === app(KoperasiContext::class)->idOrNull();
    }

    public function view(User $user, Product $product): bool
    {
        return $this->ownedByActiveKoperasi($product);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->ownedByActiveKoperasi($product);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->ownedByActiveKoperasi($product);
    }
}