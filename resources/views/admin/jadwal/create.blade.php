<x-layouts.app title="Tambah Jadwal">

    <div class="max-w-3xl mx-auto">

        <h1 class="mb-6 text-2xl font-bold text-gray-800">
            Tambah Jadwal Periksa
        </h1>

        <div class="bg-white shadow rounded-2xl p-6">

            <form action="{{ route('jadwal.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- POLI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Poli
                    </label>
                    <select name="id_poli"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Pilih Poli --</option>
                        @foreach($polis as $poli)
                            <option value="{{ $poli->id }}">
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DOKTER --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Dokter
                    </label>
                    <select name="id_dokter"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($dokters as $dokter)
                            <option value="{{ $dokter->id }}">
                                {{ $dokter->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- HARI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Hari
                    </label>
                    <select name="hari"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>

                {{-- JAM --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Mulai
                        </label>
                        <input type="time" name="jam_mulai"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Selesai
                        </label>
                        <input type="time" name="jam_selesai"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-2 pt-4">

                    <a href="{{ url()->previous() }}"
                       class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow">
                        Simpan Jadwal
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layouts.app>