<x-layouts.app title="Tambah Jadwal">

<h1 class="mb-4 text-xl font-bold">Tambah Jadwal Periksa</h1>

<form action="{{ route('jadwal.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Poli</label>
        <select name="id_poli" class="form-control">
            <option value="">-- Pilih Poli --</option>
            @foreach($polis as $poli)
                <option value="{{ $poli->id }}">
                    {{ $poli->nama_poli }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Dokter</label>
        <select name="id_dokter" class="form-control">
            <option value="">-- Pilih Dokter --</option>
            @foreach($dokters as $dokter)
                <option value="{{ $dokter->id }}">
                    {{ $dokter->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Hari</label>
        <select name="hari" class="form-control">
            <option value="">-- Pilih Hari --</option>
            <option value="Senin">Senin</option>
            <option value="Selasa">Selasa</option>
            <option value="Rabu">Rabu</option>
            <option value="Kamis">Kamis</option>
            <option value="Jumat">Jumat</option>
            <option value="Sabtu">Sabtu</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control">
    </div>

    <div class="mb-3">
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control">
    </div>

    <button class="btn btn-primary">
        Simpan Jadwal
    </button>

</form>

</x-layouts.app>