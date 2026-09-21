<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bangun Datar</title>
</head>
<body>
    <h1>Luas &amp; Keliling Bangun Datar</h1>

    <h2>Persegi</h2>
    <form action="/flat-shape/persegi" method="POST">
        @csrf
        <label>Sisi: <input type="number" name="sisi" required></label>
        <button type="submit">Hitung</button>
    </form>

    <h2>Lingkaran</h2>
    <form action="/flat-shape/lingkaran" method="POST">
        @csrf
        <label>Jari-jari: <input type="number" name="jari_jari" required></label>
        <button type="submit">Hitung</button>
    </form>
</body>
</html>
