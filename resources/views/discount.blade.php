<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Diskon Belanja</title>
</head>
<body>
    <h1>Hitung Diskon Belanja</h1>

    <form action="/discount/calculate" method="POST">
        @csrf
        <label>Harga: <input type="number" name="harga" required></label><br>
        <label>Persen Diskon (%): <input type="number" name="persen_diskon" required></label><br>
        <button type="submit">oke</button>
    </form>
</body>
</html>

