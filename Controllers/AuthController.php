<?php
// controllers/AuthController.php
require_once 'models/Alumno.php';
require_once 'models/Administrador.php';

class AuthController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $_POST['usuario'];
            $pass = $_POST['password'];

            // SI LA BASE NO ESTÁ LISTA, USAMOS UN USUARIO DE PRUEBA
            if ($this->db == null) {
                session_start();
                $_SESSION['id'] = 1;
                $_SESSION['nombre'] = "Estudiante Prueba";
                $_SESSION['rol'] = 'alumno';
                header("Location: index.php?action=dashboard");
                exit();
            }

            // Lógica real (se activará cuando conecten Supabase)
            $adminModel = new Administrador($this->db);
            $usuario = $adminModel->validar($user, $pass);
            $rol = 'admin';

            if (!$usuario) {
                $alumnoModel = new Alumno($this->db);
                $usuario = $alumnoModel->validar($user, $pass);
                $rol = 'alumno';
            }

            if ($usuario) {
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $rol;
                header("Location: index.php?action=dashboard");
            } else {
                header("Location: index.php?error=1");
            }
        }
    }

    public function guardarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Si la base no está lista, simulamos que guardó
            if ($this->db == null) {
                header("Location: index.php?action=login&registrado=1");
                exit();
            }
            
            // Lógica real para Supabase
            $alumnoModel = new Alumno($this->db);
            if ($alumnoModel->registrar($_POST['nombre'], $_POST['email'], $_POST['carnet'], $_POST['password'])) {
                header("Location: index.php?action=login&registrado=1");
            } else {
                header("Location: index.php?action=registro&error=1");
            }
        }
    }
}