<x-layouts.app title="Pembayaran Pasien">

<h2 class="text-xl font-bold mb-4">Pembayaran</h2>

<table class="w-full border">
    <thead>
        <tr class="bg-gray-200">
            <th>Poli</th>
            <th>Total</th>
            <th>Status</th>
            <th>Bukti</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembayarans as $p)
        <tr class="text-center border">
            <td>{{ $p->periksa->daftarPoli->jadwal->poli->nama_poli }}</td>
            <td>Rp {{ number_format($p->total_bayar) }}</td>
            <td>
                @if($p->status == 'lunas')
                    <span class="text-green-600 font-bold">Lunas</span>
                @else
                    <span class="text-yellow-600">Pending</span>
                @endif
            </td>
            <td>
                @if($p->bukti_pembayaran)
                    <img src="{{ asset('storage/' . $p->bukti_pembayaran) }}" width="80">
                @else
                    -
                @endif
            </td>
            <td>
                @if(!$p->bukti_pembayaran)
                <form action="{{ route('pasien.pembayaran.upload', $p->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="bukti" required>
                    <button class="bg-blue-500 text-white px-2 py-1 rounded">Upload</button>
                </form>
                @else
                    ✔ Sudah upload
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</x-layouts.app>