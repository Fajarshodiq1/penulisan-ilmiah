<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Card</title>
    <style>
        /* Mengatur tampilan body agar card berada di tengah */
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        /* Mengatur tampilan card */
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 300px;
        }

        /* Mengatur tampilan judul */
        .card h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        /* Mengatur tampilan teks di dalam card */
        .card p {
            margin: 10px 0;
            font-size: 1em;
        }

        /* Mengatur tampilan link yang berbentuk seperti button */
        .start-button {
            display: inline-block;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-size: 1em;
            margin-top: 20px;
            padding: 10px 20px;
            text-decoration: none;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        /* Mengatur tampilan link ketika di-hover */
        .start-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Student Information</h2>
        <p><strong>Nama:</strong> Naufal</p>
        <p><strong>NIM:</strong> 123456789</p>
        <p><strong>Jurusan:</strong> Computer Science</p>
        <p><strong>Semester:</strong> 6</p>
        <p><strong>Mata Kuliah:</strong> Web Development</p>
        <a href="/admin" class="start-button">Start</a>
    </div>
</body>

</html>
