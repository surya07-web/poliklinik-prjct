<x-layouts.app title="Konfirmasi Pembayaran Pasien">

<h2 class="text-xl font-bold mb-4">Verifikasi Pembayaran</h2>

<table class="w-full border">
    <thead>
        <tr class="bg-gray-200 text-center">
            <th>Pasien</th>
            <th>Total</th>
            <th>Status</th>
            <th>Bukti</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembayarans as $p)
        <tr class="border text-center">
            <td>{{ $p->periksa->daftarPoli->pasien->name }}</td>
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
                    Belum upload
                @endif
            </td>
            <td>
                @if($p->status == 'pending' && $p->bukti_pembayaran)
                <form action="{{ route('admin.pembayaran.konfirmasi', $p->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-500 text-white px-3 py-1 rounded">
                        Konfirmasi
                    </button>
                </form>
                @else
                    -
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</x-layouts.app>