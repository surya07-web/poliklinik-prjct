<x-layouts.app title="Struk Periksa">

<div class="container mt-4">

    <div class="card p-4">

        <h3 class="text-center mb-4">STRUK PEMERIKSAAN</h3>

        <hr>

        <p><b>Nama Pasien:</b> 
            {{ $periksa->daftarPoli?->pasien?->name ?? '-' }}
        </p>

        <p><b>Poli:</b> 
            {{ $periksa->daftarPoli?->jadwal?->poli?->nama ?? '-' }}
        </p>

        <p><b>Tanggal:</b> 
            {{ $periksa->tanggal_periksa 
                ? \Carbon\Carbon::parse($periksa->tanggal_periksa)->format('d-m-Y') 
                : '-' }}
        </p>

        <p><b>Keluhan:</b> 
            {{ $periksa->daftarPoli?->keluhan ?? '-' }}
        </p>

        <p><b>Catatan Dokter:</b> 
            {{ $periksa->catatan ?? '-' }}
        </p>

        <hr>

        <h5>Detail Obat</h5>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Harga</th>
                </tr>
            </thead>

            <tbody>
                @php $totalObat = 0; @endphp

                @forelse($periksa->detailPeriksa as $d)
                    <tr>
                        <td>{{ $d->obat?->nama_obat ?? '-' }}</td>
                        <td>
                            Rp {{ number_format($d->obat?->harga ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    @php $totalObat += $d->obat->harga ?? 0; @endphp
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada obat</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <hr>

        @php
            $total = $periksa->biaya_periksa ?? 0;
            $biayaDokter = $total - $totalObat;
        @endphp

        <h5>Rincian Biaya</h5>

        <p>Biaya Periksa: Rp {{ number_format($biayaDokter, 0, ',', '.') }}</p>
        <p>Total Obat: Rp {{ number_format($totalObat, 0, ',', '.') }}</p>

        <hr>

        <h4 class="text-end text-success">
            Total Bayar: Rp {{ number_format($total, 0, ',', '.') }}
        </h4>

        <div class="text-end mt-3">
            <button onclick="window.print()" class="btn btn-primary">
                Print
            </button>
        </div>

    </div>

</div>

</x-layouts.app>