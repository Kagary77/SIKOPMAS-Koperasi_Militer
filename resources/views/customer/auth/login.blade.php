<x-layouts.customer title="Masuk">
    <div class="mt-8">
        <h1 class="text-2xl font-extrabold text-zinc-900">Masuk Akun</h1>
        <p class="mt-1 text-sm text-zinc-500">Silakan masuk untuk mulai berbelanja.</p>

        <form method="POST" action="{{ route('customer.login') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-zinc-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500 focus:outline-none">
                @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-zinc-700">Password</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full rounded-lg bg-red-600 py-3 text-sm font-bold text-white hover:bg-red-700">Masuk</button>
        </form>
        
        <p class="mt-4 text-center text-sm text-zinc-500">Belum punya akun? <a href="{{ route('customer.register') }}" class="font-bold text-red-600">Daftar di sini</a></p>
    </div>
</x-layouts.customer>