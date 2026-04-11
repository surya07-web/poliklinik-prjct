<x-layouts.app title="Pasien Dashboard">

<h1 class="text-2xl font-bold mb-6">Dashboard Pasien</h1>

{{-- 🔥 STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl shadow p-5">
        <p class="text-sm opacity-80">Total Kunjungan</p>
        <h2 class="text-3xl font-bold mt-2">{{ $totalKunjungan }}</h2>
    </div>

    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl shadow p-5">
        <p class="text-sm opacity-80">Pemeriksaan Selesai</p>
        <h2 class="text-3xl font-bold mt-2">{{ $totalSelesai }}</h2>
    </div>

    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-2xl shadow p-5">
        <p class="text-sm opacity-80">Total Biaya</p>
        <h2 class="text-2xl font-bold mt-2">
            Rp {{ number_format($totalBiaya, 0, ',', '.') }}
        </h2>
    </div>

</div>


{{-- 🔴 BANNER ANTRIAN AKTIF --}}
@if($antrianAktif)
<div class="bg-gradient-to-br from-indigo-600 to-blue-700 text-white rounded-2xl shadow p-6 mb-6">

    <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold">🔴 Antrian Aktif</h2>

        <div class="text-right text-xs">
            <div id="live-jam" class="font-bold text-lg"></div>
            <div id="live-tanggal" class="opacity-80"></div>
        </div>
    </div>

    <div class="text-center">
        <h1 id="nomor-saya" class="text-5xl font-bold">
            {{ $antrianAktif->no_antrian }}
        </h1>
        <p class="mt-2 text-sm opacity-80">Nomor Anda</p>
    </div>

    <div class="mt-4 text-sm opacity-90 text-center">
        <p>Poli: <b>{{ $antrianAktif->jadwal?->poli?->nama }}</b></p>
        <p>Dokter: <b>{{ $antrianAktif->jadwal?->dokter?->name }}</b></p>
        <p>Jadwal: 
            <b>{{ $antrianAktif->jadwal?->hari }} | 
            {{ $antrianAktif->jadwal?->jam_mulai }} - {{ $antrianAktif->jadwal?->jam_selesai }}</b>
        </p>
    </div>

</div>
@endif


{{-- 🔴 ANTRIAN SEDANG DIPANGGIL --}}
<div class="bg-black text-white rounded-2xl shadow p-6 mb-6 text-center">

    <p class="text-sm opacity-70">Sedang Dilayani</p>

    <h1 id="nomor-sekarang" class="text-6xl font-bold text-yellow-400 tracking-widest">
        {{ $antrianSekarang->no_antrian ?? '-' }}
    </h1>

    <div class="mt-2 text-sm opacity-80">
        <p id="poli-sekarang">
            {{ $antrianSekarang->jadwal?->poli?->nama ?? '-' }}
        </p>
        <p id="dokter-sekarang">
            {{ $antrianSekarang->jadwal?->dokter?->name ?? '-' }}
        </p>
    </div>

</div>


{{-- 🟢 TABEL JADWAL --}}
<div class="bg-white rounded-2xl shadow p-5">

    <h2 class="font-semibold mb-4">Daftar Jadwal Poli</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-3">No</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Sedang Dilayani</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($jadwals as $i => $jadwal)
                    <tr class="border-b hover:bg-gray-50">

                        <td class="py-3">{{ $i+1 }}</td>
                        <td>{{ $jadwal->poli?->nama }}</td>
                        <td>{{ $jadwal->dokter?->name }}</td>
                        <td>{{ $jadwal->hari }}</td>
                        <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>

                        <td id="dipanggil-{{ $jadwal->id }}" class="font-bold text-blue-600">
                            {{ $jadwal->antrian_sekarang ?? '-' }}
                        </td>

                        <td class="text-center">
                            @if($antrianAktif)
                                <span class="text-xs px-3 py-1 bg-gray-100 text-gray-500 rounded">
                                    Sudah Terdaftar
                                </span>
                            @else
                                <form action="{{ route('pasien.daftar') }}" method="POST" class="space-y-2">
                                    @csrf

                                    <input type="hidden" name="id_jadwal" value="{{ $jadwal->id }}">

                                    <textarea name="keluhan"
                                        class="w-full text-xs border rounded-lg p-2"
                                        placeholder="Keluhan..." required></textarea>

                                    <button class="w-full text-xs bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                                        Daftar
                                    </button>
                                </form>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-4">
                            Tidak ada jadwal
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>


{{-- 🔥 SCRIPT LIVE --}}
@push('scripts')
<script>

// 🔴 LIVE ANTRIAN
setInterval(async () => {
    try {
        const res = await fetch("{{ route('antrian.live') }}")
        const data = await res.json()

        // global
        document.getElementById('nomor-sekarang').innerText =
            data?.no_antrian ?? '-'

        document.getElementById('poli-sekarang').innerText =
            data?.jadwal?.poli?.nama ?? '-'

        document.getElementById('dokter-sekarang').innerText =
            data?.jadwal?.dokter?.name ?? '-'

        // per jadwal
        data.per_jadwal?.forEach(j => {
            let el = document.getElementById('dipanggil-' + j.id)
            if(el){
                el.innerText = j.nomor ?? '-'
            }
        })

    } catch (err) {
        console.log("error live antrian")
    }
}, 2000);


// 🕒 JAM LIVE
function updateJam() {
    const now = new Date()

    const hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"][now.getDay()]
    const bulan = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nov","Des"][now.getMonth()]

    document.getElementById('live-jam').innerText =
        now.toLocaleTimeString('id-ID')

    document.getElementById('live-tanggal').innerText =
        `${hari}, ${now.getDate()} ${bulan} ${now.getFullYear()}`
}

setInterval(updateJam, 1000)
updateJam()

</script>
@endpush

</x-layouts.app>