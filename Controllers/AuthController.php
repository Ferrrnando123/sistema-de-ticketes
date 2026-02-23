<?php
// Controllers/AuthController.php

class AuthController {
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['usuario']; // El form usa el nombre 'usuario'
            $pass = $_POST['password'];

            // Llamada a Supabase Auth
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

                // Guardar en sesión
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['nombre'] = $user['user_metadata']['full_name'] ?? explode('@', $email)[0];
                $_SESSION['rol'] = $rol;
                $_SESSION['access_token'] = $data['access_token'];
                $_SESSION['mostrar_loading'] = true;

                header("Location: index.php?action=dashboard");
                exit();
            } else {
                // Error de login
                header("Location: index.php?error=1");
                exit();
            }
        }
    }
}