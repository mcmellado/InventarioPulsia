<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Iniciar sesión - Pulsia</title>

    <!-- Fuente corporativa -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url("{{ asset('index-pulsia-fondo.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .login-container {
            background: rgba(20, 20, 20, 0.92);
            padding: 2.5rem 3rem;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
            width: 360px;
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .login-container h1 span {
            color: #FFD700;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            color: #bbb;
        }

        input[type="text"],
        input[type="password"] {
            width: 90%;
            /* en lugar de 100% */
            display: block;
            margin: 0 auto 1.2rem auto;
            /* centramos con margin auto */
            padding: 0.7rem 1rem;
            border: 1px solid #333;
            border-radius: 8px;
            background: #111;
            color: #fff;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }


        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #FFD700;
            box-shadow: 0 0 8px rgba(255, 215, 0, 0.6);
        }

        button {
            width: 99%;
            background: #FFD700;
            border: none;
            padding: 0.8rem;
            font-size: 1.1rem;
            font-weight: bold;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #000;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #e6c200;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 215, 0, 0.4);
        }

        .error-message {
            background-color: rgba(255, 0, 0, 0.1);
            border: 1px solid #ff4d4d;
            color: #ff4d4d;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
        }

        .footer-text {
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #777;
        }

        .footer-text span {
            color: #FFD700;
        }

        /* Cursor efecto máquina de escribir */
        .typing::after {
            content: '|';
            animation: blink 0.7s infinite;
            color: #FFD700;
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .logo-pulsia {
            display: flex;
            justify-content: center;
        }

        .logo-pulsia img {
            max-width: 80px;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo-pulsia">
            <img src="{{ asset('logo-pulsia.svg') }}" alt="Logo Pulsia">
        </div>

        <h1><span>INICIAR </span>SESIÓN</h1>

        @if ($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <label for="user">Usuario</label>
            <input type="text" name="user" id="user" required autofocus>

            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">ACCEDER</button>
        </form>

        <div class="footer-text">
            Believe in <span id="typing-text" class="typing"></span>
        </div>




    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const text = "CREATIVE THINKING_";
            let index = 0;
            const speed = 100;
            const target = document.getElementById("typing-text");

            function typeEffect() {
                if (index < text.length) {
                    target.textContent += text.charAt(index);
                    index++;
                    setTimeout(typeEffect, speed);
                } else {
                    target.classList.remove("typing");
                }
            }

            typeEffect();
        });
    </script>
</body>

</html>