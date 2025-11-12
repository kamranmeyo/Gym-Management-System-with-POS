<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4F46E5, #9333EA);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }
        .container {
            max-width: 500px;
            background: rgba(255,255,255,0.1);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        h1 {
            font-size: 2.2rem;
            margin-bottom: 15px;
        }
        p {
            font-size: 1rem;
            opacity: 0.9;
        }
        a.button {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 28px;
            background-color: #fff;
            color: #4F46E5;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        a.button:hover {
            background-color: #4F46E5;
            color: #fff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to {{ config('app.name', 'Laravel') }}</h1>
        <p>Challenge yourself every day</p>

        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="button">Login</a>
        @endif
    </div>
</body>
</html>
