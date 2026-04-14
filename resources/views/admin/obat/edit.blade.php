<x-layouts.app title="Edit Obat">

    <div class="max-w-2xl mx-auto">

        <h1 class="mb-6 text-2xl font-bold text-gray-800">
            Edit Obat
        </h1>

        <div class="bg-white shadow rounded-2xl p-6">

            <form action="{{ route('obat.update', $obat->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- NAMA OBAT --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Obat
                    </label>
                    <input type="text" name="nama_obat"
                        value="{{ old('nama_obat', $obat->nama_obat) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Masukkan nama obat" required>

                    @error('nama_obat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- HARGA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Harga
                    </label>
                    <input type="number" name="harga"
                        value="{{ old('harga', $obat->harga) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                        placeholder="Contoh: 50000" min="0" required>

                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- STOK --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Stok
                    </label>
                    <input type="number" name="stok"
                        value="{{ old('stok', $obat->stok) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                        placeholder="Masukkan jumlah stok" min="0" required>

                    @error('stok')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-2 pt-4">

                    <a href="{{ route('obat.index') }}"
                       class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow">
                        Update
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layouts.app>