<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>419 | Sesi Kedaluwarsa</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdfdfd;
            color: #333;
            text-align: center;
            padding-top: 100px;
        }
        h1 {
            font-size: 72px;
            color: #ff6b6b;
        }
        p {
            font-size: 20px;
            margin-bottom: 30px;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #2779bd;
        }
    </style>
</head>
<body>
    <h1>419</h1>
    <p>Sesi Anda telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.</p>
    <a href="{{ url()->previous() }}">Kembali</a>
</body>
</html>
