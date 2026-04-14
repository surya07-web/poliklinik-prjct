<x-layouts.app title="Detail Pemeriksaan">

<h1 class="text-2xl font-bold mb-6">Detail Pemeriksaan</h1>

<div class="bg-white rounded-2xl shadow p-6">

    <p><b>Poli:</b> {{ $periksa->daftarPoli->jadwal->poli->nama }}</p>
    <p><b>Dokter:</b> {{ $periksa->daftarPoli->jadwal->dokter->name }}</p>
    <p><b>Tanggal:</b> {{ $periksa->tanggal_periksa }}</p>

    <hr class="my-4">

    <p><b>Catatan Dokter:</b></p>
    <p class="mb-4">{{ $periksa->catatan }}</p>

    <p><b>Obat:</b></p>
    <ul class="list-disc ml-6 mb-4">
        @foreach($periksa->detailPeriksa as $d)
            <li>{{ $d->obat->nama_obat }}</li>
        @endforeach
    </ul>

    <p class="font-bold text-lg">
        Total Biaya: Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
    </p>

</div>

</x-layouts.app>