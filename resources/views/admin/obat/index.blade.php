<x-layouts.app title="Data Obat">

<div class="p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Data Obat
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Daftar seluruh obat poliklinik
            </p>
        </div>

        <a href="{{ route('obat.create') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl shadow transition">

            <i class="fas fa-plus"></i>
            Tambah Obat
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
                            Nama Obat
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-600 uppercase">
                            Harga
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-600 uppercase">
                            Stok
                        </th>

                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-600 uppercase">
                            Aksi
                        </th>

                    </tr>
                </thead>


                {{-- TABLE BODY --}}
                <tbody class="divide-y divide-slate-100">

                    @forelse($obats as $i => $obat)

                    <tr class="hover:bg-slate-50 transition">

                        {{-- NO --}}
                        <td class="px-6 py-5 text-slate-700 font-medium">
                            {{ $i + 1 }}
                        </td>

                        {{-- NAMA --}}
                        <td class="px-6 py-5">
                            <div class="font-semibold text-slate-800">
                                {{ $obat->nama_obat }}
                            </div>
                        </td>

                        {{-- HARGA --}}
                        <td class="px-6 py-5 text-slate-700">
                            Rp {{ number_format($obat->harga, 0, ',', '.') }}
                        </td>

                        {{-- STOK --}}
                        <td class="px-6 py-5">

                            @if($obat->stok <= 5)

                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                    {{ $obat->stok }} Stok
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-600">
                                    {{ $obat->stok }} Stok
                                </span>

                            @endif

                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-5">

                            <div class="flex justify-end gap-3">

                                {{-- EDIT --}}
                                <a href="{{ route('obat.edit', $obat->id) }}"
                                    class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-500 text-white px-4 py-2 rounded-xl text-sm font-medium transition">

                                    <i class="fas fa-pen"></i>
                                    Edit
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('obat.destroy', $obat->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Hapus obat ini?')"
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
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                            Data obat kosong
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</x-layouts.app>