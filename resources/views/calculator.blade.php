<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Calculator Sederhana</title>
</head>
<body>
    <h1>Calculator Sederhana</h1>

    <form action="/calculator/add" method="POST">
        @csrf
        <input type="number" name="number1" required>
        <input type="number" name="number2" required>
        <button type="submit">Tambah</button>
    </form>

    <form action="/calculator/subtract" method="POST">
        @csrf
        <input type="number" name="number1" required>
        <input type="number" name="number2" required>
        <button type="submit">Kurang</button>
    </form>

    <form action="/calculator/multiply" method="POST">
        @csrf
        <input type="number" name="number1" required>
        <input type="number" name="number2" required>
        <button type="submit">Kali</button>
    </form>

    <form action="/calculator/divide" method="POST">
        @csrf
        <input type="number" name="number1" required>
        <input type="number" name="number2" required>
        <button type="submit">Bagi</button>
    </form>
</body>
</html>
