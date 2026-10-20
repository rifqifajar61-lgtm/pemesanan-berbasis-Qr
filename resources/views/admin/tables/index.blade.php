@extends('admin.layouts.app')

@section('title', 'Meja & QR Code')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Meja & QR Code</h1>
        <p class="text-xs text-gray-500">Setiap meja punya QR Code yang mengarah ke halaman menu</p>
    </div>
    <a href="{{ route('admin.tables.create') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow">
        + Tambah Meja
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-xs mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                <th class="p-4 w-12">No</th>
                <th class="p-4">Nomor Meja</th>
                <th class="p-4">Link Menu</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
            @forelse($tables as $table)
                @php $url = route('home', ['table' => $table->token]); @endphp
                <tr class="hover:bg-gray-50">
                    <td class="p-4 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="p-4 font-bold text-gray-900">Meja {{ $table->table_number }}</td>
                    <td class="p-4 text-xs text-gray-500 break-all">{{ $url }}</td>
                    <td class="p-4 text-center space-x-2 whitespace-nowrap">
                        <button type="button"
                                data-url="{{ $url }}"
                                data-label="Meja {{ $table->table_number }}"
                                class="btn-qr text-green-600 hover:underline text-xs font-bold">
                            Lihat QR
                        </button>
                        <a href="{{ route('admin.tables.edit', $table->id) }}" class="text-blue-600 hover:underline text-xs font-bold">Edit</a>
                        <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus meja ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-400 text-xs font-semibold">
                        Belum ada meja. Silakan klik "+ Tambah Meja".
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal QR Code --}}
<div id="qr-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-xs text-center">
        <h3 id="qr-label" class="text-lg font-black text-gray-800 mb-4"></h3>
        <div id="qr-canvas" class="flex justify-center mb-4"></div>
        <p class="text-[11px] text-gray-400 mb-4">Scan untuk membuka menu dan memesan</p>
        <div class="flex gap-2 justify-center">
            <a id="qr-download" href="#" download="qr-meja.png"
               class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Unduh PNG</a>
            <button type="button" id="qr-close"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const modal = document.getElementById('qr-modal');
    const box = document.getElementById('qr-canvas');
    const label = document.getElementById('qr-label');
    const download = document.getElementById('qr-download');

    document.querySelectorAll('.btn-qr').forEach(btn => {
        btn.addEventListener('click', () => {
            box.innerHTML = '';
            new QRCode(box, {
                text: btn.dataset.url,
                width: 220,
                height: 220,
                correctLevel: QRCode.CorrectLevel.M
            });
            label.textContent = btn.dataset.label;
            download.download = 'qr-' + btn.dataset.label.toLowerCase().replace(/\s+/g, '-') + '.png';

            // qrcodejs mengisi <img> sesaat setelah canvas dibuat
            setTimeout(() => {
                const img = box.querySelector('img');
                const canvas = box.querySelector('canvas');
                download.href = img && img.src ? img.src : canvas.toDataURL('image/png');
            }, 100);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.getElementById('qr-close').addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
</script>
@endpush