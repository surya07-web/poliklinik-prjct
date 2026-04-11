<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Layar Antrian</title>
    @vite(['resources/css/app.css'])

    <style>
        body {
            margin: 0;
            background: #0f172a;
            color: white;
            font-family: sans-serif;
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center h-screen text-center">

    <h1 class="text-4xl font-bold mb-6">
        POLIKLINIK SEHAT
    </h1>

    {{-- NOMOR BESAR --}}
    <div id="nomor" class="text-[150px] font-bold text-yellow-400">
        -
    </div>

    {{-- INFO --}}
    <div class="text-xl mt-4 space-y-2">
        <p>Poli: <span id="poli">-</span></p>
        <p>Dokter: <span id="dokter">-</span></p>
    </div>

    {{-- JAM --}}
    <div class="absolute bottom-5 text-sm text-gray-400">
        <span id="jam"></span>
    </div>

</body>

<script>
    async function loadData() {
        const res = await fetch('/display/data')
        const data = await res.json()

        document.getElementById('nomor').innerText =
            data?.no_antrian ?? '-'

        document.getElementById('poli').innerText =
            data?.jadwal?.poli?.nama ?? '-'

        document.getElementById('dokter').innerText =
            data?.jadwal?.dokter?.name ?? '-'
    }

    // refresh tiap 3 detik
    setInterval(loadData, 3000)
    loadData()

    // JAM LIVE
    setInterval(() => {
        const now = new Date()
        document.getElementById('jam').innerText =
            now.toLocaleTimeString()
    }, 1000)
</script>

</html>