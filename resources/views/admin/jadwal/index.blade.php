<x-layouts.app title="Data Jadwal">

<div class="p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Data Jadwal Periksa
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Daftar jadwal praktik dokter
            </p>
        </div>

        <a href="{{ route('jadwal.create') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl shadow transition">

            <i class="fas fa-plus"></i>
            Tambah Jadwal
        </a>

    </div>


    {{-- CARD TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">

        <div class="overflow-x-auto">

            <table class="w-full">

                {{-- TABLE HEAD --}}
                <thead class="bg-slate-50 border-b border-slate-100">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-600 uppercase">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-600 uppercase">
                            Poli
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-600 uppercase">
                            Dokter
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-600 uppercase">
                            Hari
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-600 uppercase">
                            Jam Praktik
                        </th>

                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-600 uppercase">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- TABLE BODY --}}
                <tbody class="divide-y divide-slate-100">

                    @forelse($jadwals as $i => $j)

                    <tr class="hover:bg-slate-50 transition">

                        {{-- NO --}}
                        <td class="px-6 py-5 text-slate-700 font-medium">
                            {{ $i + 1 }}
                        </td>

                        {{-- POLI --}}
                        <td class="px-6 py-5">

                            <div class="font-semibold text-slate-800">
                                {{ $j->poli->nama_poli ?? '-' }}
                            </div>

                        </td>

                        {{-- DOKTER --}}
                        <td class="px-6 py-5 text-slate-700">
                            {{ $j->dokter->name ?? '-' }}
                        </td>

                        {{-- HARI --}}
                        <td class="px-6 py-5">

                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-600">
                                {{ $j->hari }}
                            </span>

                        </td>

                        {{-- JAM --}}
                        <td class="px-6 py-5 text-slate-700">

                            {{ $j->jam_mulai }} - {{ $j->jam_selesai }}

                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-5">

                            <div class="flex justify-end gap-3">

                                {{-- EDIT --}}
                                <a href="{{ route('jadwal.edit', $j->id) }}"
                                    class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-500 text-white px-4 py-2 rounded-xl text-sm font-medium transition">

                                    <i class="fas fa-pen"></i>
                                    Edit

                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('jadwal.destroy', $j->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Yakin hapus jadwal ini?')"
                                        class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-medium transition">

                                        <i class="fas fa-trash"></i>
                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6"
                            class="px-6 py-10 text-center text-slate-500">

                            Belum ada data jadwal

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</x-layouts.app>