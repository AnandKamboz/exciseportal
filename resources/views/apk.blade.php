<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Karheteshi App</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #005bea, #00c6fb);
            color: white;
        }

        .box {
            text-align: center;
            background: rgba(255, 255, 255, 0.12);
            padding: 50px 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 34px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .btn-download {
            background: #34d058;
            color: white;
            padding: 16px 40px;
            border-radius: 40px;
            font-size: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s ease;
            display: inline-block;
        }

        .btn-download:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        }

    </style>
</head>

<body>

    <div class="box">
        <h1>📱 Download Karheteshi App</h1>

        <a href="{{ asset('apk') }}" download class="btn-download">
            Download APK
        </a>
    </div>

</body>
</html>
