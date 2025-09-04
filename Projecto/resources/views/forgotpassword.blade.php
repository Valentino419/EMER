<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - User Login</title>
  <style>
    body {
      background: linear-gradient(135deg, #e6f4ff, #cfe8ff);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    /* Botón link */
    .btn-forgot {
      background: none;
      border: none;
      color: #0099ff;
      font-size: 15px;
      cursor: pointer;
      text-decoration: underline;
    }

    /* Fondo oscuro */
    .modal-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.45);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    /* Caja modal */
    .modal-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 18px;
      padding: 30px 25px;
      width: 360px;
      text-align: center;
      position: relative;
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
      animation: fadeIn 0.35s ease-in-out;
      backdrop-filter: blur(10px);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-25px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .modal-header h2 {
      font-size: 22px;
      margin-bottom: 10px;
      color: #007acc;
    }

    .modal-header p {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }

    input[type="email"] {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0 20px;
      border-radius: 10px;
      border: 1px solid #b3daff;
      font-size: 14px;
      color: #333;
      outline: none;
      transition: all 0.2s;
    }

    input[type="email"]:focus {
      border-color: #0099ff;
      box-shadow: 0 0 6px rgba(0, 153, 255, 0.3);
    }

    button {
      background: linear-gradient(135deg, #00c6ff, #0072ff);
      color: white;
      border: none;
      padding: 12px 20px;
      width: 100%;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: linear-gradient(135deg, #0099ff, #0066cc);
    }

    .close-btn {
      position: absolute;
      top: 10px; right: 15px;
      background: none;
      border: none;
      font-size: 20px;
      color: #666;
      cursor: pointer;
    }

    .alert-success {
      font-size: 14px;
      color: #2e8b57;
      margin-bottom: 10px;
    }

    .text-danger {
      font-size: 13px;
      color: #d9534f;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>

        @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Send reset link</button>
    </form>
    </div>
</body>
