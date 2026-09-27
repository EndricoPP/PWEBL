{{-- Praktikum 3 --}}
@extends('layouts.main')

@section('title', 'Katalog Item')

@section('content')

    <div class="card">
        <h3>Katalog Barang (Data Multi Array)</h3>
        <p>Baris berwarna <span style="background-color: #ffc0cb; padding: 2px 6px;">merah muda</span> menandakan item dengan diskon &ge; 20%.</p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Item</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Potongan</th>
                    <th>Harga Bersih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($katalog as $barang)
                    @php
                        $nilaiDiskon = $barang['harga'] * $barang['diskon_persen'] / 100;
                        $hargaBersih = $barang['harga'] - $nilaiDiskon;
                    @endphp
                    <tr @if($barang['diskon_persen'] >= 20) style="background-color: #ffc0cb;" @endif>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge">{{ $barang['kode'] }}</span></td>
                        <td>{{ $barang['nama_item'] }}</td>
                        <td>{{ $barang['kategori'] }}</td>
                        <td>Rp {{ number_format($barang['harga'], 0, ',', '.') }}</td>
                        <td>{{ $barang['diskon_persen'] }}%</td>
                        <td>Rp {{ number_format($nilaiDiskon, 0, ',', '.') }}</td>
                        <td><strong>Rp {{ number_format($hargaBersih, 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada barang di katalog.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
