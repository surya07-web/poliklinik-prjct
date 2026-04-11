<x-layouts.app title="Dokter Dashboard">

    <h1 class="mb-4">Dashboard Dokter</h1>

    <div class="card p-3">
        <h5>Antrian Pasien</h5>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Poli</th>
                    <th>No Antrian</th>
                    <th>Status</th>
                    <th>Obat</th>
                    <th>Total Biaya</th> {{-- 🔥 tambahan --}}
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($antrians as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>

                    <td>{{ $a->pasien->name ?? '-' }}</td>

                    <td>{{ $a->jadwal?->poli?->nama ?? '-' }}</td>

                    <td><b>{{ $a->no_antrian }}</b></td>

                    {{-- STATUS --}}
                    <td>
                        @if(!$a->periksa)
                            <span class="badge bg-secondary">Menunggu</span>

                        @elseif(!$a->periksa->catatan)
                            <span class="badge bg-warning text-dark">Dipanggil</span>

                        @else
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>

                    {{-- OBAT --}}
                    <td>
                        @if($a->periksa && $a->periksa->detailPeriksa && $a->periksa->detailPeriksa->count())
                            @foreach($a->periksa->detailPeriksa as $d)
                                <span class="badge bg-info text-dark">
                                    {{ $d->obat->nama_obat ?? '-' }}
                                </span>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>

                    {{-- 🔥 TOTAL BIAYA --}}
                    <td>
                        @if($a->periksa && $a->periksa->biaya_periksa)
                            <span class="fw-bold text-success">
                                Rp {{ number_format($a->periksa->biaya_periksa, 0, ',', '.') }}
                            </span>
                        @else
                            -
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td>

                        {{-- BELUM DIPANGGIL --}}
                        @if(!$a->periksa)
                            <form action="{{ route('dokter.panggil', $a->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-success">
                                    Panggil
                                </button>
                            </form>

                        {{-- SUDAH DIPANGGIL --}}
                        @elseif(!$a->periksa->catatan)
                            <a href="{{ route('dokter.periksa', $a->periksa->id) }}"
                               class="btn btn-sm btn-primary">
                                Periksa
                            </a>

                        {{-- SELESAI --}}
                        @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                Selesai
                            </button>
                        @endif

                        {{-- RIWAYAT --}}
                        @if($a->periksa)
                            <a href="{{ route('dokter.riwayat', $a->pasien->id) }}"
                               class="btn btn-sm btn-info">
                                Riwayat
                            </a>
                        @endif

                        @if($a->periksa && $a->periksa->catatan)
                            <a href="{{ route('dokter.struk', $a->periksa->id) }}"
                            class="btn btn-sm btn-dark">
                                Struk
                            </a>
                        @endif

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada antrian
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</x-layouts.app>