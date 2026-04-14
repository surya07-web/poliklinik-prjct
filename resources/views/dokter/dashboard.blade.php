<x-layouts.app title="Dokter Dashboard">

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Dashboard Dokter
            </h1>
            <p class="text-sm text-gray-500">
                Kelola antrian pasien hari ini
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('dokter.export.jadwal') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                Export Jadwal
            </a>

            <a href="{{ route('dokter.export.riwayat') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                Export Riwayat
            </a>
        </div>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">
                Antrian Pasien Hari Ini
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                {{-- HEADER --}}
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pasien</th>
                        <th class="px-6 py-4">Poli</th>
                        <th class="px-6 py-4">Antrian</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Obat</th>
                        <th class="px-6 py-4">Biaya</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y">

                    @forelse($antrians as $i => $a)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- NO --}}
                        <td class="px-6 py-4 font-medium">
                            {{ $i + 1 }}
                        </td>

                        {{-- PASIEN --}}
                        <td class="px-6 py-4">
                            {{ $a->pasien->name ?? '-' }}
                        </td>

                        {{-- POLI --}}
                        <td class="px-6 py-4">
                            {{ $a->jadwal?->poli?->nama ?? '-' }}
                        </td>

                        {{-- ANTRIAN --}}
                        <td class="px-6 py-4 font-semibold">
                            #{{ $a->no_antrian }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">
                            @if(!$a->periksa)
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-200 text-gray-700">
                                    Menunggu
                                </span>

                            @elseif(!$a->periksa->catatan)
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                    Dipanggil
                                </span>

                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Selesai
                                </span>
                            @endif
                        </td>

                        {{-- OBAT --}}
                        <td class="px-6 py-4">
                            @if($a->periksa && $a->periksa->detailPeriksa && $a->periksa->detailPeriksa->count())
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @foreach($a->periksa->detailPeriksa as $d)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                            {{ $d->obat->nama_obat ?? '-' }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- BIAYA --}}
                        <td class="px-6 py-4">
                            @if($a->periksa && $a->periksa->biaya_periksa)
                                <span class="font-semibold text-green-600">
                                    Rp {{ number_format($a->periksa->biaya_periksa, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4 text-center">

                            <div class="flex flex-col items-center gap-1">

                                {{-- ACTION UTAMA --}}
                                @if(!$a->periksa)
                                    <form action="{{ route('dokter.panggil', $a->id) }}" method="POST">
                                        @csrf
                                        <button class="px-4 py-1.5 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            Panggil
                                        </button>
                                    </form>

                                @elseif(!$a->periksa->catatan)
                                    <a href="{{ route('dokter.periksa', $a->periksa->id) }}"
                                    class="px-4 py-1.5 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Periksa
                                    </a>

                                @else
                                    <span class="px-4 py-1.5 text-xs bg-gray-300 text-gray-600 rounded-lg">
                                        Selesai
                                    </span>
                                @endif

                                {{-- ACTION TAMBAHAN --}}
                                @if($a->periksa)
                                    <div class="flex gap-2 text-[11px] text-gray-500">

                                        <a href="{{ route('dokter.riwayat', $a->pasien->id) }}"
                                        class="hover:text-blue-600">
                                            Riwayat
                                        </a>

                                        @if($a->periksa->catatan)
                                            <span>|</span>
                                            <a href="{{ route('dokter.struk', $a->periksa->id) }}"
                                            class="hover:text-gray-800">
                                                Struk
                                            </a>
                                        @endif

                                    </div>
                                @endif

                            </div>

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-6">
                            Tidak ada antrian pasien
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    window.Echo.channel('antrian')
        .listen('.antrian.updated', (e) => {

            console.log('Realtime masuk:', e);

            // 🔥 cara paling simpel dulu: reload otomatis
            location.reload();

        });

});
</script>

</x-layouts.app>