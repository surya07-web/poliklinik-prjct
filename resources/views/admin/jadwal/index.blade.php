<x-layouts.app title="Data Jadwal">

<h1 class="mb-4 text-xl font-bold">Data Jadwal Periksa</h1>

<a href="{{ route('jadwal.create') }}" class="btn btn-primary mb-3">
    + Tambah Jadwal
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Poli</th>
            <th>Dokter</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($jadwals as $i => $j)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $j->poli->nama_poli ?? '-' }}</td>
            <td>{{ $j->dokter->name ?? '-' }}</td>
            <td>{{ $j->hari }}</td>
            <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
            <td>
                <a href="{{ route('jadwal.edit', $j->id) }}" class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin hapus jadwal ini?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Belum ada jadwal</td>
        </tr>
        @endforelse
    </tbody>
</table>

</x-layouts.app>