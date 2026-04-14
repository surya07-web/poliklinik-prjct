<x-layouts.app title="Riwayat Pendaftaran">

<div class="max-w-6xl mx-auto">

    <h1 class="text-2xl font-bold mb-6 text-gray-800">
        Riwayat Pendaftaran Poli
    </h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                {{-- HEADER --}}
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Poli</th>
                        <th class="px-4 py-3">Dokter</th>
                        <th class="px-4 py-3">Hari</th>
                        <th class="px-4 py-3">No Antrian</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y">

                    @forelse($riwayats as $i => $r)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ $i+1 }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $r->jadwal->poli->nama }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $r->jadwal->dokter->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $r->jadwal->hari }}
                        </td>

                        <td class="px-4 py-3 font-semibold">
                            #{{ $r->no_antrian }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3">
                            @if($r->periksa)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Sudah Diperiksa
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">
                                    Belum Diperiksa
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3 text-center">
                            @if($r->periksa)
                                <a href="{{ route('pasien.detail', $r->periksa->id) }}"
                                   class="inline-block px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Detail
                                </a>
                            @else
                                <span class="text-gray-400 text-xs italic">
                                    -
                                </span>
                            @endif
                        </td>

                    </tr>
                    @empty

                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-6">
                            Belum ada riwayat pendaftaran
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

</div>

</x-layouts.app>