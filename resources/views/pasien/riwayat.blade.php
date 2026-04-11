<x-layouts.app title="Riwayat Pasien">

<h2 class="mb-4">Riwayat Pemeriksaan</h2>

<div class="card p-3">

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Poli</th>
            <th>Keluhan</th>
            <th>Catatan</th>
            <th>Obat</th>
            <th>Total Biaya</th>
        </tr>
    </thead>

    <tbody>
        @forelse($riwayat as $i => $r)
        <tr>
            <td>{{ $i + 1 }}</td>

            <td>
                {{ $r->tanggal_periksa 
                    ? \Carbon\Carbon::parse($r->tanggal_periksa)->format('d-m-Y') 
                    : '-' }}
            </td>

            <td>{{ $r->daftarPoli?->jadwal?->poli?->nama ?? '-' }}</td>

            <td>{{ $r->daftarPoli?->keluhan ?? '-' }}</td>

            <td>{{ $r->catatan ?? '-' }}</td>

            {{-- OBAT --}}
            <td>
                @if($r->detailPeriksa->count())
                    @foreach($r->detailPeriksa as $d)
                        <div>
                            {{ $d->obat->nama_obat }}
                            (Rp {{ number_format($d->obat->harga, 0, ',', '.') }})
                        </div>
                    @endforeach
                @else
                    -
                @endif
            </td>

            {{-- TOTAL --}}
            <td>
                <b class="text-success">
                    Rp {{ number_format($r->biaya_periksa ?? 0, 0, ',', '.') }}
                </b>
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="7" class="text-center">
                Belum ada riwayat
            </td>
        </tr>
        @endforelse
    </tbody>

</table>

</div>

</x-layouts.app>