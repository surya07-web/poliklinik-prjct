<x-layouts.app title="Tambah Obat">

    <h1 class="mb-4">Tambah Obat</h1>

    <form action="{{ route('obat.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Obat</label>
            <input type="text" name="nama_obat" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
    </form>

</x-layouts.app>