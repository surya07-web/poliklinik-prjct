<x-layouts.app title="Riwayat Pasien">

<h2 class="mb-4">Riwayat Pasien</h2>

<div class="card p-4">

    @forelse($riwayats as $r)

        <div class="mb-4 border p-3 rounded">

            <p><b>Tanggal:</b> {{ $r->created_at->format('d-m-Y') }}</p>
            <p><b>Poli:</b> {{ $r->jadwal->poli->nama }}</p>
            <p><b>Keluhan:</b> {{ $r->keluhan }}</p>

            @if($r->periksa)

                <p><b>Catatan Dokter:</b> {{ $r->periksa->catatan }}</p>

                <p><b>Obat:</b></p>
                <ul>
                    @php $total = 0; @endphp

                    @foreach($r->periksa->detailPeriksa as $d)
                        <li>
                            {{ $d->obat->nama_obat }} 
                            (Rp {{ number_format($d->obat->harga) }})
                        </li>

                        @php $total += $d->obat->harga; @endphp
                    @endforeach
                </ul>

                <p class="mt-2">
                    <b>Total Biaya: </b> 
                    Rp {{ number_format($total) }}
                </p>

            @else
                <span class="badge bg-warning">Belum diperiksa</span>
            @endif

        </div>

    @empty
        <p>Tidak ada riwayat</p>
    @endforelse

</div>

</x-layouts.app>