<?php

use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public int $koperasiId;
    public string $subject = '';
    public string $description = '';
    public $attachment; // File Upload

    public function mount(int $koperasiId): void
    {
        $this->koperasiId = $koperasiId;
    }

    #[Computed]
    public function complaints()
    {
        return Complaint::where('customer_id', Auth::guard('customer')->id())
            ->where('koperasi_id', $this->koperasiId)
            ->latest()
            ->get();
    }

    public function submit(): void
    {
        $this->validate([
            'subject' => 'required|string|max:150',
            'description' => 'required|string|min:10',
            'attachment' => 'nullable|image|max:2048', // Maks 2MB
        ]);

        $path = $this->attachment ? $this->attachment->store('complaints', 'public') : null;

        Complaint::create([
            'koperasi_id' => $this->koperasiId,
            'customer_id' => Auth::guard('customer')->id(),
            'subject' => $this->subject,
            'description' => $this->description,
            'attachment_path' => $path,
            'status' => 'open',
        ]);

        $this->reset('subject', 'description', 'attachment');
        unset($this->complaints); // Refresh list
        session()->flash('success', 'Pengaduan berhasil dikirim.');
    }
};
?>

<div class="space-y-6">
    @if(session()->has('success'))
        <div class="rounded-lg bg-green-100 p-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    {{-- Form Pengaduan --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
        <h2 class="mb-4 font-bold text-zinc-900">Buat Pengaduan Baru</h2>
        
        <form wire:submit="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-zinc-700">Subjek</label>
                <input type="text" wire:model="subject" class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500 focus:outline-none">
                @error('subject') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700">Deskripsi Keluhan</label>
                <textarea wire:model="description" rows="3" class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500 focus:outline-none" placeholder="Jelaskan kendala Anda..."></textarea>
                @error('description') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700">Foto Bukti (Opsional)</label>
                <input type="file" wire:model="attachment" class="mt-1 w-full text-sm text-zinc-500 file:mr-4 file:rounded-full file:border-0 file:bg-red-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-red-700 hover:file:bg-red-100">
                @error('attachment') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" class="w-full rounded-lg bg-red-600 py-3 text-sm font-bold text-white hover:bg-red-700">
                Kirim Pengaduan
            </button>
        </form>
    </div>

    {{-- Riwayat Pengaduan --}}
    <div>
        <h2 class="mb-3 font-bold text-zinc-900">Riwayat Pengaduan</h2>
        <div class="space-y-3">
            @forelse($this->complaints as $complaint)
                <div class="rounded-xl border border-zinc-200 bg-white p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-zinc-900">{{ $complaint->subject }}</h3>
                            <p class="text-xs text-zinc-500">{{ $complaint->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="rounded-full px-2 py-1 text-[10px] font-bold uppercase 
                            {{ $complaint->status === 'open' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $complaint->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $complaint->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $complaint->status }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-zinc-600">{{ $complaint->description }}</p>
                    
                    @if($complaint->admin_reply)
                        <div class="mt-3 rounded-lg bg-zinc-50 p-3 text-sm">
                            <p class="font-semibold text-zinc-900">Balasan Koperasi:</p>
                            <p class="text-zinc-600">{{ $complaint->admin_reply }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-zinc-300 p-6 text-center text-sm text-zinc-500">
                    Belum ada riwayat pengaduan.
                </div>
            @endforelse
        </div>
    </div>
</div>