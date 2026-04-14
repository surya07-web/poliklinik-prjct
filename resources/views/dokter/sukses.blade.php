<x-layouts.app title="Berhasil">

<div class="flex items-center justify-center min-h-[70vh]">

    <div class="text-center">

        {{-- ICON CENTANG --}}
        <div class="mb-6">
            <div class="w-24 h-24 mx-auto rounded-full bg-green-100 flex items-center justify-center">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
        </div>

        {{-- TEXT --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            Pemeriksaan Berhasil
        </h1>

        <p class="text-gray-500 mb-6">
            Data pasien sudah berhasil disimpan
        </p>

        {{-- BUTTON --}}
        <a href="{{ route('dokter.dashboard') }}"
           class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            Kembali ke Dashboard
        </a>

    </div>

</div>

</x-layouts.app>