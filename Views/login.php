<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus IT Tickets - UDB</title>
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <h1 class="title">Campus IT Tickets</h1>
            <p class="subtitle">Gestión de Infraestructura UDB</p>
            
            <?php if(isset($_GET['registrado'])): ?>
                <p style="color: #28a745; text-align: center; font-weight: bold; font-size: 0.9rem; margin-bottom: 10px;">
                    ¡Registro exitoso! Ya puedes entrar, pa.
                </p>
            <?php endif; ?>

            <form action="index.php?action=procesar_login" method="POST">
                <div class="input-group">
                    <label for="usuario">Dirección de email o usuario</label>
                    <input type="text" id="usuario" name="usuario" required placeholder="ejemplo@udb.edu.sv">
                </div>

                <div class="input-group">
                    <label for="password">Clave</label>
                    <input type="password" id="password" name="password" required>
                    <div class="form-footer">
                        <a href="#" class="link">¿Olvidó su clave?</a>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Iniciar sesión</button>
            </form>

            <p class="footer-text">¿No tiene una cuenta? <a href="index.php?action=registro" class="link">Regístrese</a></p>
        </div>
    </div>
</body>
</html>