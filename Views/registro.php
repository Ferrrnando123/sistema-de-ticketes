<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Alumnos - UDB</title>
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <h1 class="title">Crear Cuenta</h1>
            <p class="subtitle">Regístrese para reportar daños en la UDB</p>
            
            <form action="index.php?action=guardar_registro" method="POST">
                <div class="input-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" required placeholder="Juan Pérez">
                </div>
                <div class="input-group">
                    <label>Correo Institucional</label>
                    <input type="email" name="email" required placeholder="usuario@udb.edu.sv">
                </div>
                <div class="input-group">
                    <label>Carnet</label>
                    <input type="text" name="carnet" required placeholder="AB123456">
                </div>
                <div class="input-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">Registrarse</button>
            </form>

            <p class="footer-text">¿Ya tiene cuenta? <a href="index.php?action=login" class="link">Inicie Sesión</a></p>
        </div>
    </div>
</body>
</html>