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
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        require 'views/dashboard.php';
        break;

    // NUEVAS RUTAS AGREGADAS
    case 'nuevo-ticket':
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        require 'views/nuevo-ticket.php';
        break;

    case 'mis-tickets':
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        require 'views/mis-tickets.php';
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?action=login");
        break;

    default:
        echo "404 - Esta página no existe en la UDB, pa";
        break;
}