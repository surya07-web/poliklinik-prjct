<x-layouts.app title="Edit Jadwal">

<h1 class="text-2xl font-bold mb-6">Edit Jadwal Poli</h1>

<div class="bg-white p-6 rounded-xl shadow max-w-xl">

    <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- POLI --}}
        <div class="mb-4">
            <label class="block mb-1">Poli</label>
            <select name="id_poli" class="w-full border rounded-lg p-2" required>
                @foreach($polis as $poli)
                    <option value="{{ $poli->id }}"
                        {{ $jadwal->id_poli == $poli->id ? 'selected' : '' }}>
                        {{ $poli->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- DOKTER --}}
        <div class="mb-4">
            <label class="block mb-1">Dokter</label>
            <select name="id_dokter" class="w-full border rounded-lg p-2" required>
                @foreach($dokters as $dokter)
                    <option value="{{ $dokter->id }}"
                        {{ $jadwal->id_dokter == $dokter->id ? 'selected' : '' }}>
                        {{ $dokter->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- HARI --}}
        <div class="mb-4">
            <label class="block mb-1">Hari</label>
            <input type="text" name="hari" value="{{ $jadwal->hari }}"
                class="w-full border rounded-lg p-2" required>
        </div>

        {{-- JAM --}}
        <div class="mb-4">
            <label class="block mb-1">Jam Mulai</label>
            <input type="time" name="jam_mulai" value="{{ $jadwal->jam_mulai }}"
                class="w-full border rounded-lg p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Jam Selesai</label>
            <input type="time" name="jam_selesai" value="{{ $jadwal->jam_selesai }}"
                class="w-full border rounded-lg p-2" required>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Update
        </button>

    </form>

</div>

</x-layouts.app>