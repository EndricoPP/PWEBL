<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Praktikum Transaksi Penjualan AJAX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Transaksi Penjualan</h5>
            <span>Tanggal: {{ date('d-m-Y') }}</span>
        </div>
        <div class="card-body">

            <div id="alertContainer"></div>

            <div class="mb-3">
                <button type="button" class="btn btn-success" id="btnOpenModal">
                    + Pilih Item Barang
                </button>
            </div>

            <!-- Tabel Keranjang Penjualan -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Barang</th>
                            <th style="width: 150px;">Harga (Rp)</th>
                            <th style="width: 120px;">Qty</th>
                            <th style="width: 180px;">Subtotal (Rp)</th>
                            <th style="width: 80px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada barang dipilih. Klik tombol "+ Pilih Item Barang".
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Subtotal (Rp):</th>
                            <th colspan="2" id="subtotalText">0</th>
                        </tr>
                        <!-- Tantangan 1: Diskon (%) -->
                        <tr>
                            <th colspan="4" class="text-end align-middle">Diskon (%):</th>
                            <th colspan="2">
                                <input type="number" class="form-control form-control-sm" id="discountInput"
                                       value="0" min="0" max="100" step="0.01" style="max-width: 120px;">
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end fw-bold">Grand Total (Rp):</th>
                            <th colspan="2" class="fw-bold text-primary fs-5" id="grandTotalText">0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-end mt-3">
                <button type="button" class="btn btn-primary btn-lg" id="btnSubmitTransaction" disabled>
                    Simpan Transaksi
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="searchItemInput" placeholder="Cari nama barang...">
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemListBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
    let cart = [];

    const rupiah = n => n.toLocaleString('id-ID');
    const esc = s => $('<div>').text(s).html();

    // Buka Modal
    $('#btnOpenModal').click(function () {
        fetchItems('');
        itemModal.show();
    });

    // Pencarian Barang
    $('#searchItemInput').on('keyup', function () {
        fetchItems($(this).val());
    });

    function fetchItems(search) {
        $.ajax({
            url: "{{ route('transactions.get-items') }}",
            type: "GET",
            data: { search: search },
            success: function (res) {
                let html = '';
                if (res.data.length > 0) {
                    $.each(res.data, function (i, item) {
                        // Tantangan 2: stok <= 3 tampil merah "Stok Menipis"
                        let badge = item.stock <= 3
                            ? `<span class="badge bg-danger">${item.stock} - Stok Menipis</span>`
                            : `<span class="badge bg-info text-dark">${item.stock}</span>`;

                        html += `
                            <tr>
                                <td>${item.id}</td>
                                <td>${esc(item.name)}</td>
                                <td>Rp ${rupiah(parseFloat(item.price))}</td>
                                <td>${badge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success btn-select"
                                        data-id="${item.id}" data-name="${esc(item.name)}"
                                        data-price="${item.price}" data-stock="${item.stock}">
                                        Pilih
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center text-muted">Barang tidak ditemukan.</td></tr>';
                }
                $('#itemListBody').html(html);
            }
        });
    }

    // Pilih Barang ke Keranjang
    $(document).on('click', '.btn-select', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let price = parseFloat($(this).data('price'));
        let stock = parseInt($(this).data('stock'));

        let index = cart.findIndex(x => x.item_id === id);

        if (index !== -1) {
            if (cart[index].quantity + 1 > stock) {
                alert('Jumlah melebihi stok!');
                return;
            }
            cart[index].quantity += 1;
        } else {
            cart.push({ item_id: id, name: name, price: price, stock: stock, quantity: 1 });
        }

        renderCart();
        itemModal.hide();
    });

    function getDiscount() {
        let d = parseFloat($('#discountInput').val());
        if (isNaN(d) || d < 0) d = 0;
        return d > 100 ? 100 : d;
    }

    // Tantangan 1: hitung total setelah diskon
    function updateTotals() {
        let subtotal = cart.reduce((sum, x) => sum + x.price * x.quantity, 0);
        let grandTotal = subtotal - (subtotal * getDiscount() / 100);
        $('#subtotalText').text('Rp ' + rupiah(subtotal));
        $('#grandTotalText').text('Rp ' + rupiah(grandTotal));
    }

    function renderCart() {
        if (cart.length === 0) {
            $('#cartBody').html('<tr><td colspan="6" class="text-center text-muted py-4">Belum ada barang dipilih.</td></tr>');
            updateTotals();
            $('#btnSubmitTransaction').prop('disabled', true);
            return;
        }

        let html = '';

        $.each(cart, function (i, item) {
            let subtotal = item.price * item.quantity;

            html += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${esc(item.name)}</td>
                    <td>Rp ${rupiah(item.price)}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm input-qty"
                               data-index="${i}" value="${item.quantity}" min="1" max="${item.stock}">
                    </td>
                    <td>Rp ${rupiah(subtotal)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger btn-remove" data-index="${i}">&times;</button>
                    </td>
                </tr>`;
        });

        $('#cartBody').html(html);
        updateTotals();
        $('#btnSubmitTransaction').prop('disabled', false);
    }

    // Ubah Diskon
    $('#discountInput').on('input change', updateTotals);

    // Ubah Qty
    $(document).on('change', '.input-qty', function () {
        let i = $(this).data('index');
        let qty = parseInt($(this).val()) || 1;

        if (qty > cart[i].stock) {
            alert('Stok tidak cukup!');
            qty = cart[i].stock;
        }

        cart[i].quantity = qty;
        renderCart();
    });

    // Hapus Item
    $(document).on('click', '.btn-remove', function () {
        cart.splice($(this).data('index'), 1);
        renderCart();
    });

    // Simpan Transaksi
    $('#btnSubmitTransaction').click(function () {
        let payload = {
            discount_percent: getDiscount(),
            items: cart.map(x => ({ item_id: x.item_id, quantity: x.quantity, price: x.price }))
        };

        $.ajax({
            url: "{{ route('transactions.store') }}",
            type: "POST",
            data: JSON.stringify(payload),
            contentType: "application/json",
            success: function (res) {
                if (res.status) {
                    // Tantangan 3: tombol cetak struk
                    $('#alertContainer').html(`
                        <div class="alert alert-success d-flex justify-content-between align-items-center">
                            <span>${res.message} No. Invoice: <strong>${res.invoice}</strong></span>
                            <a href="${res.receipt_url}" target="_blank" class="btn btn-sm btn-dark">Cetak Struk</a>
                        </div>`);
                    cart = [];
                    $('#discountInput').val(0);
                    renderCart();
                }
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Terjadi kesalahan sistem.');
            }
        });
    });
});
</script>

</body>
</html>
