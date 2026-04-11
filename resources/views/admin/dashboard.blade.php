<x-layouts.app title="Admin Dashboard">

    <h1 class="mb-4 text-xl font-bold">Dashboard Admin</h1>

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

</x-layouts.app>