<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus IT Tickets - UDB</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2322d3ee'><path d='M2 8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 1 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 1 0 0-4V8z'/></svg>">
    <!-- aqui realizamos la carga del loader condicional -->
    <?php include 'Views/partials/loader.php'; ?>
    <style>
        /* CSS Integrado para que el hosting no moleste */
        :root {
            --primary-color: #76bc21; /* Verde UDB */
            --dark-blue: #1a237e;
            --bg-color: #f4f7fe;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-body {
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* Para asegurar que se centre verticalmente */
        }

        .login-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .login-box {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .title { color: var(--dark-blue); font-size: 24px; margin-bottom: 8px; margin-top: 0; }
        .subtitle { color: #666; font-size: 14px; margin-bottom: 2rem; }

        .input-group { text-align: left; margin-bottom: 1.5rem; }
        .input-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #333; }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .form-footer {
            margin-top: 5px;
            text-align: right;
        }

        .btn-primary {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-primary:hover { background-color: #65a31d; }
        .link { color: #0066cc; text-decoration: none; font-size: 13px; }
        .footer-text { margin-top: 1.5rem; color: #555; font-size: 14px; }
    </style>
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <!-- aqui realizamos el titulo principal del sistema -->
            <h1 class="title">Campus IT Tickets</h1>
            <p class="subtitle">Gestión de Infraestructura UDB</p>
            
            <?php if(isset($_GET['registrado'])): ?>
                <p style="color: #28a745; text-align: center; font-weight: bold; font-size: 0.9rem; margin-bottom: 10px;">
                    ¡Registro exitoso! Ya puedes entrar, pa.
                </p>
            <?php endif; ?>

            <!-- aqui realizamos el inicio de sesión enviando al controlador -->
            <form action="index.php?action=procesar_login" method="POST">
                <div class="input-group">
                    <label for="usuario">Correo Institucional</label>
                    <input type="email" id="usuario" name="usuario" required>
                </div>

                <!-- aqui realizamos el campo de clave (hemos quitado el boton de olvidar clave) -->
                <div class="input-group">
                    <label for="password">Clave</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-primary">Iniciar sesión</button>
            </form>

            <p class="footer-text">Acceso exclusivo para personal y alumnos UDB</p>
        </div>
    </div>
</body>
</html>