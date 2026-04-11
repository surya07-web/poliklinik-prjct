<x-layouts.app title="Periksa Pasien">

<h2>Periksa Pasien</h2>

<div class="card p-4">

    <p><b>Nama:</b> {{ $periksa->daftarPoli?->pasien?->name ?? '-' }}</p>
    <p><b>Poli:</b> {{ $periksa->daftarPoli?->jadwal?->poli?->nama ?? '-' }}</p>
    <p><b>Keluhan:</b> {{ $periksa->daftarPoli?->keluhan ?? '-' }}</p>

    <form action="{{ route('dokter.periksa.simpan', $periksa->id) }}" method="POST">
        @csrf

        {{-- CATATAN --}}
        <div class="mb-3">
            <label>Catatan Dokter</label>
            <textarea name="catatan" class="form-control" required></textarea>
        </div>

        {{-- BIAYA --}}
        <div class="mb-3">
            <label>Biaya Periksa</label>
            <input type="number" name="biaya_periksa" id="biaya" class="form-control" placeholder="Masukkan biaya dokter" required>
        </div>

        {{-- OBAT --}}
        <div class="mb-3">
            <label class="fw-bold">Pilih Obat (lihat stok)</label>

            @foreach($obats as $obat)
                <div class="form-check">

                    <input 
                        type="checkbox" 
                        class="obat-check"
                        data-harga="{{ $obat->harga }}"
                        name="obat[]" 
                        value="{{ $obat->id }}"
                        {{ $obat->stok == 0 ? 'disabled' : '' }}
                    >

                    {{ $obat->nama_obat }} 
                    (Rp {{ number_format($obat->harga) }})

                    {{-- 🔥 STOK --}}
                    @if($obat->stok == 0)
                        <span class="badge bg-danger">Habis</span>
                    @elseif($obat->stok <= 5)
                        <span class="badge bg-warning text-dark">
                            Stok: {{ $obat->stok }}
                        </span>
                    @else
                        <span class="badge bg-success">
                            Stok: {{ $obat->stok }}
                        </span>
                    @endif

                </div>
            @endforeach

        </div>

        {{-- TOTAL --}}
        <div class="mb-3">
            <label>Total Biaya</label>
            <input type="text" id="total" class="form-control" readonly>
        </div>

        <button class="btn btn-primary">Simpan</button>

    </form>

</div>

{{-- 🔥 JAVASCRIPT TOTAL OTOMATIS --}}
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

</x-layouts.app>