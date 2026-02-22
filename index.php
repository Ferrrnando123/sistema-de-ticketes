<?php
// index.php
require_once 'config/db.php'; 
session_start();

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        require 'views/login.php';
        break;

    case 'procesar_login':
        require 'controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->procesarLogin();
        break;

    case 'registro':
        require 'views/registro.php';
        break;

    case 'guardar_registro':
        require 'controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->guardarRegistro();
        break;

    case 'dashboard':
        validarSesion();
        require 'views/dashboard.php';
        break;

    case 'nuevo-ticket':
        validarSesion();
        require 'views/nuevo-ticket.php';
        break;

    case 'mis-tickets':
        validarSesion();
        require 'views/mis-tickets.php';
        break;

    // ESTA ES LA RUTA QUE FALTABA PARA EL NAV
    case 'panel-soporte':
        validarSesion();
        require 'views/panelsoporte.php'; 
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?action=login");
        exit();
        break;

    default:
        http_response_code(404);
        echo "404 - Esta página no existe en la UDB, pa";
        break;
}

// Función auxiliar para no repetir código en cada caso
function validarSesion() {
    if (!isset($_SESSION['id'])) {
        header("Location: index.php?action=login");
        exit();
    }
}