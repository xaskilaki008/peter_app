<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Вход</title>
<style>
    * {
        box-sizing: border-box;
    }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: #f8f8f8;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-box {
        background: #fff;
        padding: 25px 30px;
        border-radius: 8px;
        width: 320px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        box-sizing: border-box;
    }
    h2 {
        margin: 0 0 20px;
        font-weight: 700;
        color: #333;
        text-align: center;
    }
    input {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 0.95rem;
        transition: border-color 0.2s ease;
        box-sizing: border-box;
    }
    input:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 5px rgba(13,110,253,0.5);
    }
    button {
        width: 100%;
        padding: 12px 0;
        background: #0d6efd;
        color: white;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    button:hover {
        background: #084cdf;
    }
    .link {
        text-align: center;
        margin-top: 15px;
        font-size: 0.9rem;
        color: #555;
    }
    .link a {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    .link a:hover {
        color: #084cdf;
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="form-box">
    <h2>Вход</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        @if ($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                <ul style="padding-left: 20px; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input type="email" name="email" placeholder="Email" required autofocus>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>
    <div class="link">
        Нет аккаунта? <a href="{{ route('register') }}">Регистрация</a>
    </div>
</div>
</body>
</html>
