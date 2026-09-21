<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bangun Ruang</title>
</head>
<body>
    <h1>Volume &amp; Luas Permukaan Bangun Ruang</h1>

    <h2>Kubus</h2>
    <form action="/solid-shape/kubus" method="POST">
        @csrf
        <label>Sisi: <input type="number" name="sisi" required></label>
        <button type="submit">Hitung</button>
    </form>

    <h2>Balok</h2>
    <form action="/solid-shape/balok" method="POST">
        @csrf
        <label>Panjang: <input type="number" name="panjang" required></label>
        <label>Lebar: <input type="number" name="lebar" required></label>
        <label>Tinggi: <input type="number" name="tinggi" required></label>
        <button type="submit">Hitung</button>
    </form>

    <h2>Tabung</h2>
    <form action="/solid-shape/tabung" method="POST">
        @csrf
        <label>Jari-jari: <input type="number" name="jari_jari" required></label>
        <label>Tinggi: <input type="number" name="tinggi" required></label>
        <button type="submit">Hitung</button>
    </form>
</body>
</html>
