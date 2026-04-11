<x-layouts.app title="Data Obat">

    <h1 class="mb-4">Data Obat</h1>

    <a href="{{ route('obat.create') }}" class="btn btn-primary mb-3">
        + Tambah Obat
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($obats as $i => $obat)
            <tr>
                <td>{{ $i + 1 }}</td>

                <td>{{ $obat->nama_obat }}</td>

                <td>Rp {{ number_format($obat->harga) }}</td>

                {{-- 🔥 STOK --}}
                <td>
                    @if($obat->stok <= 5)
                        <span class="badge bg-danger">
                            {{ $obat->stok }}
                        </span>
                    @else
                        <span class="badge bg-success">
                            {{ $obat->stok }}
                        </span>
                    @endif
                </td>

                {{-- 🔥 AKSI --}}
                <td>
                    <a href="{{ route('obat.edit', $obat->id) }}"
                        class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form action="{{ route('obat.destroy', $obat->id) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus obat ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" class="text-center">
                    Data obat kosong
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>

</x-layouts.app>