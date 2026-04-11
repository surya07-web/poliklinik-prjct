<x-layouts.app title="Edit Obat">

    <h1 class="mb-4">Edit Obat</h1>

    <form action="{{ route('obat.update', $obat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Obat</label>
            <input type="text" name="nama_obat"
                value="{{ $obat->nama_obat }}"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga"
                value="{{ $obat->harga }}"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" value="{{ $obat->stok }}" class="form-control" required>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>

</x-layouts.app>