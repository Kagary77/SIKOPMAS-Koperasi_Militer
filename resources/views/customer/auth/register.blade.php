<x-layouts.customer title="Daftar">
    <div class="mt-8">
        <h1 class="text-2xl font-extrabold text-zinc-900">Daftar Akun Baru</h1>
        <p class="mt-1 text-sm text-zinc-500">Lengkapi data diri Anda.</p>

        <form method="POST" action="{{ route('customer.register') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-zinc-700">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500 focus:outline-none">
                @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-zinc-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500 focus:outline-none">
                @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-zinc-700">No. WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500 focus:outline-none">
                @error('phone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-zinc-700">Password (Min. 8 Karakter)</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500 focus:outline-none">
                @error('password') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="w-full rounded-lg bg-red-600 py-3 text-sm font-bold text-white hover:bg-red-700">Daftar Sekarang</button>
        </form>
        
        <p class="mt-4 text-center text-sm text-zinc-500">Sudah punya akun? <a href="{{ route('customer.login') }}" class="font-bold text-red-600">Masuk di sini</a></p>
    </div>
</x-layouts.customer>