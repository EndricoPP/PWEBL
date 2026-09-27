{{-- Praktikum 3 --}}
@extends('layouts.main')

@section('title', 'Transaksi Penjualan Item')

@section('content')

    <!-- 1. Menampilkan Data Skalar -->
    <div class="card">
        <h3>1. Informasi Transaksi (Data Skalar)</h3>
        <p><strong>No. Nota:</strong> {{ $noNota }}</p>
        <p><strong>Kasir Tugas:</strong> {{ $kasir }}</p>
        <p><strong>Diskon / Potongan:</strong> Rp {{ number_format($potongan, 0, ',', '.') }}</p>
    </div>

    <!-- 2. Menampilkan Data Array 1D -->
    <div class="card">
        <h3>2. Opsi Pembayaran (Data Array 1D)</h3>
        <ul>
            @foreach($metodeBayar as $metode)
                <li>{{ $metode }}</li>
            @endforeach
        </ul>
    </div>

    <!-- 3. Menampilkan Data Multi-Array 2D dengan Table & @@forelse -->
    <div class="card">
        <h3>3. Detail Item Terjual (Data Multi Array 2D)</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Item</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Status Stok</th>
                </tr>
            </thead>
            <tbody>
                @php $totalSemua = 0; @endphp

                @forelse($items as $item)
                    @php
                        $subtotal = $item['harga'] * $item['qty'];
                        $totalSemua += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge">{{ $item['kode'] }}</span></td>
                        <td>{{ $item['nama'] }}</td>
                        <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        <td>
                            @if($item['stok'] > 0)
                                Tersedia ({{ $item['stok'] }})
                            @else
                                <span class="text-danger">Stok Habis!</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada item terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align: right;">Total Gross:</th>
                    <th colspan="2">Rp {{ number_format($totalSemua, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" style="text-align: right;">Total Net (Setelah Potongan):</th>
                    <th colspan="2">Rp {{ number_format($totalSemua - $potongan, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- 4. Mengolah Data JSON di Blade dan JavaScript -->
    <div class="card">
        <h3>4. Informasi Store (Data JSON)</h3>
        @php $tokoObj = json_decode($infoToko); @endphp

        <p><strong>Toko:</strong> {{ $tokoObj->nama_toko }} ({{ $tokoObj->cabang }})</p>

        <button onclick="cekStatusToko()" style="padding: 8px 15px; background: #2a9d8f; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Cek Status Toko (JS Alert)
        </button>

        <script>
            function cekStatusToko() {
                // Parsing JSON Blade ke JavaScript
                const dataToko = @json(json_decode($infoToko));
                alert("Nama Toko: " + dataToko.nama_toko + "\nStatus Operasional: " + dataToko.status);
            }
        </script>
    </div>

@endsection
