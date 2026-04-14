<x-layouts.app title="Periksa Pasien">

<h1 class="text-2xl font-bold mb-6">Periksa Pasien</h1>

{{-- 🔥 HALAMAN SUKSES --}}
@if(session('done'))

<div class="flex flex-col items-center justify-center bg-white rounded-2xl shadow p-10 max-w-xl mx-auto">

    {{-- ICON CENTANG --}}
    <div class="text-green-500 text-7xl mb-4">
        <i class="fas fa-circle-check"></i>
    </div>

    <h2 class="text-xl font-bold mb-2">Pemeriksaan Selesai</h2>

    <p class="text-gray-500 mb-6 text-center">
        Data pasien berhasil disimpan
    </p>

</div>

{{-- 🔥 AUTO REDIRECT OPSIONAL --}}
<script>
    setTimeout(() => {
        window.location.href = "{{ route('dokter.dashboard') }}";
    }, 2000);
</script>

@else


{{-- 🔴 ALERT ERROR --}}
@if(session('error'))
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif


<div class="bg-white rounded-2xl shadow p-6 max-w-2xl">

    {{-- DATA PASIEN --}}
    <div class="mb-4 space-y-1 text-sm">
        <p><b>Nama:</b> {{ $periksa->daftarPoli?->pasien?->name ?? '-' }}</p>
        <p><b>Poli:</b> {{ $periksa->daftarPoli?->jadwal?->poli?->nama ?? '-' }}</p>
        <p><b>Keluhan:</b> {{ $periksa->daftarPoli?->keluhan ?? '-' }}</p>
    </div>

    <form action="{{ route('dokter.periksa.simpan', $periksa->id) }}" method="POST">
        @csrf

        {{-- CATATAN --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Catatan Dokter</label>
            <textarea 
                name="catatan" 
                class="w-full border rounded-lg p-2"
                required></textarea>
        </div>

        {{-- BIAYA --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Biaya Periksa</label>
            <input 
                type="number" 
                name="biaya_periksa" 
                id="biaya"
                class="w-full border rounded-lg p-2"
                placeholder="Masukkan biaya dokter"
                required>
        </div>

        {{-- OBAT --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Pilih Obat</label>

            <div class="space-y-2">
                @foreach($obats as $obat)
                    <label class="flex items-center justify-between border rounded-lg p-2">

                        <div class="flex items-center gap-2">
                            <input 
                                type="checkbox"
                                class="obat-check"
                                data-harga="{{ $obat->harga }}"
                                name="obat[]"
                                value="{{ $obat->id }}"
                                {{ $obat->stok == 0 ? 'disabled' : '' }}
                            >

                            <span>
                                {{ $obat->nama_obat }} 
                                <span class="text-xs text-gray-500">
                                    (Rp {{ number_format($obat->harga,0,',','.') }})
                                </span>
                            </span>
                        </div>

                        {{-- STATUS STOK --}}
                        <div>
                            @if($obat->stok == 0)
                                <span class="text-xs px-2 py-1 bg-red-500 text-white rounded">
                                    Habis
                                </span>
                            @elseif($obat->stok <= 5)
                                <span class="text-xs px-2 py-1 bg-yellow-400 text-black rounded">
                                    Stok: {{ $obat->stok }}
                                </span>
                            @else
                                <span class="text-xs px-2 py-1 bg-green-500 text-white rounded">
                                    Stok: {{ $obat->stok }}
                                </span>
                            @endif
                        </div>

                    </label>
                @endforeach
            </div>
        </div>

        {{-- TOTAL --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Total Biaya</label>
            <input 
                type="text" 
                id="total" 
                class="w-full border rounded-lg p-2 bg-gray-100"
                readonly>
        </div>

        {{-- BUTTON --}}
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
            Simpan Pemeriksaan
        </button>

    </form>

</div>


{{-- 🔥 SCRIPT TOTAL OTOMATIS --}}
<script>
    const checkboxes = document.querySelectorAll('.obat-check');
    const biayaInput = document.getElementById('biaya');
    const totalInput = document.getElementById('total');

    function hitungTotal() {
        let total = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseInt(cb.dataset.harga);
            }
        });

        let biaya = parseInt(biayaInput.value) || 0;
        total += biaya;

        totalInput.value = "Rp " + total.toLocaleString('id-ID');
    }

    checkboxes.forEach(cb => cb.addEventListener('change', hitungTotal));
    biayaInput.addEventListener('input', hitungTotal);
</script>

@endif

</x-layouts.app>