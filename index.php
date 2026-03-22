<?php
// index.php
// Permitir origen dinámico para que React (localhost:5173) pueda enviar cookies
$origin = $_SERVER['HTTP_ORIGIN'] ?? 'http://localhost:5173';
header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Manejo de preflight requests para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');

require_once 'Config/supabase.php'; 
session_start();

$action = $_GET['action'] ?? 'login';

// Función auxiliar para retornar respuestas JSON estandarizadas
function jsonResponse($status, $success, $message, $data = null) {
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit();
}

// aqui realizamos el filtrado de rutas por acciones para la API
switch ($action) {
    case 'procesar_login':
        require 'Controllers/AuthController.php';
        $auth = new AuthController();
        $auth->procesarLogin();
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
            jsonResponse(403, false, 'Acceso denegado: Se requiere rol de administrador');
        }
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->panelSoporte();
        break;

    case 'actualizar_estado':
        validarSesion();
        if ($_SESSION['rol'] !== 'admin') {
            jsonResponse(403, false, 'Acceso denegado: Se requiere rol de administrador');
        }
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->actualizarEstado();
        break;

    case 'logout':
        session_destroy();
        jsonResponse(200, true, 'Sesión cerrada exitosamente');
        break;

    case 'check_session':
        if (isset($_SESSION['id'])) {
            jsonResponse(200, true, 'Sesión activa', [
                'id' => $_SESSION['id'],
                'email' => $_SESSION['email'],
                'nombre' => $_SESSION['nombre'],
                'rol' => $_SESSION['rol']
            ]);
        } else {
            jsonResponse(401, false, 'No hay sesión activa');
        }
        break;

    default: // endpoints que solo mostraban vista ya no son necesarios en el backend (ej. dashboard, login form, ayuda)
        if (in_array($action, ['login', 'dashboard', 'nuevo-ticket', 'ayuda'])) {
            // Si el cliente pide estas vistas a la API, simplemente decimos que React maneja esto
            jsonResponse(200, true, 'La ruta es manejada por React en el frontend');
        } else {
            jsonResponse(404, false, 'Endpoint no encontrado en la API rutera');
        }
        break;
}

// Función auxiliar para validar que el usuario tenga sesión activa
function validarSesion() {
    if (!isset($_SESSION['id'])) {
        jsonResponse(401, false, 'No autorizado. Por favor inicie sesión.');
    }
}