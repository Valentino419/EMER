<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
         body {
            background-color: #f4f5fa;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            width: 350px;
        }

        .login-container::before, .login-container::after {
            content: '';
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 200px;
            width: 200px;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            z-index: -1;
            border-radius: 30px;
            opacity: 0.4;
        }

        .login-container::before { left: -120px; }
        .login-container::after { right: -120px; }

        .icon {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            margin: -60px auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        h2 { margin-bottom: 10px; font-size: 24px; color: #333; }

        p.subtitle { color: #aaa; font-size: 14px; margin-bottom: 30px; }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .remember {
            display: flex;
            align-items: center;
            justify-content: left;
            font-size: 14px;
            margin: 15px 0;
            color: #555;
        }

        button {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .links {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .links a {
            color: #0072ff;
            text-decoration: underline;
            margin: 0 5px;
        }
        .hidden { display: none; }
        
    </style>
</head>
<body>
    <div class="login-container">
        <div class="icon">
            👤
        </div>
        <h2>USER LOGIN</h2>
        <p class="subtitle"></p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" name="email" placeholder="USERNAME" required>
            <input type="password" name="password" placeholder="PASSWORD" required>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember" style="margin-right: 8px;">
                <label for="remember">remember me</label>
            </div>

            <button type="submit">LOGIN</button>
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </form>

        <!-- <div class="links"> 
            <a href="{{ route('password.request') }}">Forgot password?</a> |
            <a href="{{ route('register') }}">Create account</a>
        </div> -->

        {{-- REGISTER --}}
        <div id="form-register" class="hidden">
            <h2>REGISTER</h2>
        <p class="subtitle">Create your account</p>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="text" name="name" placeholder="FULL NAME" required>
                <input type="email" name="email" placeholder="EMAIL" required>
                <input type="password" name="password" placeholder="PASSWORD" required>
                <input type="password" name="password_confirmation" placeholder="CONFIRM PASSWORD" required>

                <button type="submit">REGISTER</button>
            </form>
        <div class="links">
                <a onclick="showForm('form-login')">Back to login</a>
            </div>
    </div>

    </div>
</body>
</html>
