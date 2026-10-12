<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $transaction->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .receipt { max-width: 420px; font-family: 'Courier New', monospace; }
        @media print { .no-print { display: none; } body { background: #fff; } }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="receipt mx-auto bg-white p-4 shadow-sm">
        <h5 class="text-center mb-0">STRUK PENJUALAN</h5>
        <p class="text-center small mb-3">Praktikum Transaksi Penjualan</p>

        <div class="small">
            <div>No. Invoice : {{ $transaction->invoice_number }}</div>
            <div>Tanggal     : {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</div>
        </div>
        <hr>

        @foreach ($details as $d)
            <div class="small">
                <div>{{ $d->name }}</div>
                <div class="d-flex justify-content-between">
                    <span>{{ $d->quantity }} x {{ number_format($d->price, 0, ',', '.') }}</span>
                    <span>{{ number_format($d->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
        <hr>

        <div class="small">
            <div class="d-flex justify-content-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaction->subtotal_amount, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Diskon ({{ rtrim(rtrim(number_format($transaction->discount_percent, 2, '.', ''), '0'), '.') }}%)</span>
                <span>- Rp {{ number_format($transaction->subtotal_amount - $transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between fw-bold fs-6 mt-1">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        <hr>
        <p class="text-center small mb-0">Terima kasih atas kunjungan Anda</p>
    </div>

    <div class="text-center mt-3 no-print">
        <button class="btn btn-primary" onclick="window.print()">Cetak</button>
        <a href="{{ route('transactions.create') }}" class="btn btn-outline-secondary">Transaksi Baru</a>
    </div>
</div>
</body>
</html>
