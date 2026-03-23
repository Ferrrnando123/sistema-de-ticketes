<?php
// Controllers/AuthController.php

class AuthController {
    // esta es la funcion para procesar el inicio de sesión
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Soporte para FormData o JSON application/json
            $input = json_decode(file_get_contents('php://input'), true);
            $email = $_POST['usuario'] ?? $input['usuario'] ?? '';
            $pass = $_POST['password'] ?? $input['password'] ?? '';

            if (empty($email) || empty($pass)) {
                jsonResponse(400, false, 'Usuario y contraseña son requeridos');
            }

            // aqui conectamos con la api de supabase para validar el usuario
            $response = Supabase::request('/auth/v1/token?grant_type=password', 'POST', [
                'email' => $email,
                'password' => $pass
            ]);

            if ($status = $response['status'] == 200) {
                $data = $response['data'];
                $user = $data['user'];

                // Detección de rol por dominio
                $rol = 'alumno'; // Default
                if (strpos($email, '@udb.edu.sv') !== false && strpos($email, '@alumno.udb.edu.sv') === false) {
                    $rol = 'admin';
                }

                // aqui guardamos los datos del usuario en la sesion
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['nombre'] = $user['user_metadata']['full_name'] ?? explode('@', $email)[0];
                $_SESSION['rol'] = $rol;
                $_SESSION['access_token'] = $data['access_token'];
                // aqui hacemos el cambio para mostrar el loader solo al loguear
                $_SESSION['mostrar_loading'] = true;

                // aqui devolvemos los datos del usuario en JSON
                jsonResponse(200, true, 'Login exitoso', [
                    'id' => $_SESSION['id'],
                    'email' => $_SESSION['email'],
                    'nombre' => $_SESSION['nombre'],
                    'rol' => $_SESSION['rol']
                ]);
            } else {
                // Error de login
                jsonResponse(401, false, 'Credenciales incorrectas');
            }
        }
    }
}