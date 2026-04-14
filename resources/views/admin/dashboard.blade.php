<x-layouts.app title="Admin Dashboard">

    <h1 class="mb-4 text-xl font-bold">Dashboard Admin</h1>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="p-4 bg-blue-500 text-white rounded-xl shadow">
            <h5>Total Pasien</h5>
            <h2 class="text-2xl font-bold">{{ $jumlahPasien }}</h2>
        </div>

        <div class="p-4 bg-green-500 text-white rounded-xl shadow">
            <h5>Total Dokter</h5>
            <h2 class="text-2xl font-bold">{{ $jumlahDokter }}</h2>
        </div>

        <div class="p-4 bg-yellow-500 text-white rounded-xl shadow">
            <h5>Total Periksa</h5>
            <h2 class="text-2xl font-bold">{{ $jumlahPeriksa }}</h2>
        </div>

    </div>

    {{-- EXPORT DATA --}}
    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-3">Export Data</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- EXPORT DOKTER --}}
            <a href="{{ route('admin.export.dokter') }}"
               class="p-4 bg-white rounded-xl shadow hover:shadow-lg transition flex items-center justify-between border">
                
                <div>
                    <p class="text-sm text-gray-500">Data Dokter</p>
                    <h3 class="font-semibold text-gray-800">Export Excel</h3>
                </div>

                <i class="fas fa-file-excel text-green-600 text-xl"></i>
            </a>

            {{-- EXPORT PASIEN --}}
            <a href="{{ route('admin.export.pasien') }}"
               class="p-4 bg-white rounded-xl shadow hover:shadow-lg transition flex items-center justify-between border">
                
                <div>
                    <p class="text-sm text-gray-500">Data Pasien</p>
                    <h3 class="font-semibold text-gray-800">Export Excel</h3>
                </div>

                <i class="fas fa-file-excel text-green-600 text-xl"></i>
            </a>

            {{-- EXPORT OBAT --}}
            <a href="{{ route('admin.export.obat') }}"
               class="p-4 bg-white rounded-xl shadow hover:shadow-lg transition flex items-center justify-between border">
                
                <div>
                    <p class="text-sm text-gray-500">Data Obat</p>
                    <h3 class="font-semibold text-gray-800">Export Excel</h3>
                </div>

                <i class="fas fa-file-excel text-green-600 text-xl"></i>
            </a>

        </div>
    </div>

</x-layouts.app>