<?php
// index.php
require_once 'Config/supabase.php'; 
session_start();

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        require 'Views/login.php';
        break;

    case 'procesar_login':
        require 'Controllers/AuthController.php';
        $auth = new AuthController();
        $auth->procesarLogin();
        break;

    case 'dashboard':
        validarSesion();
        require 'Views/dashboard.php';
        break;

    case 'nuevo-ticket':
        validarSesion();
        require 'Views/nuevo-ticket.php';
        break;

    case 'guardar_ticket':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->guardar();
        break;

    case 'mis-tickets':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->misTickets();
        break;

    case 'panel-soporte':
        validarSesion();
        if ($_SESSION['rol'] !== 'admin') {
            header("Location: index.php?action=dashboard");
            exit();
        }
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->panelSoporte();
        break;

    case 'actualizar_estado':
        validarSesion();
        if ($_SESSION['rol'] !== 'admin') {
            header("Location: index.php?action=dashboard");
            exit();
        }
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->actualizarEstado();
        break;
    
    case 'ayuda':
        validarSesion();
        require 'Views/ayuda.php';
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