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
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

bootstrapSesionDesdeToken();

$action = $_GET['action'] ?? 'login';

// Función auxiliar para retornar respuestas JSON estandarizadas
function jsonResponse($status, $success, $message, $data = null) {
    http_response_code($status);
    $response = json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    if ($response === false) {
        $response = json_encode([
            'success' => false, 
            'message' => 'Error de codificación JSON: ' . json_last_error_msg(),
            'data' => null
        ]);
    }
    echo $response;
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

    case 'get_recent_tickets':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->recentTickets();
        break;

    case 'ticket_detalle':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->detalle();
        break;

    case 'buscar_faq':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->buscarFaq();
        break;

    case 'mensajes_ticket_listar':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->listarMensajesTicket();
        break;

    case 'mensajes_ticket_enviar':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->enviarMensajeTicket();
        break;

    case 'notificaciones_listar':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->listarNotificaciones();
        break;

    case 'notificaciones_marcar_leida':
        validarSesion();
        require 'Controllers/TicketController.php';
        $tickets = new TicketController();
        $tickets->marcarNotificacionLeida();
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
    bootstrapSesionDesdeToken();
    if (!isset($_SESSION['id'])) {
        jsonResponse(401, false, 'No autorizado. Por favor inicie sesión.');
    }
}

function bootstrapSesionDesdeToken() {
    if (isset($_SESSION['id'])) {
        return true;
    }

    $token = obtenerBearerToken();
    if (!$token) {
        return false;
    }

    $response = Supabase::request('/auth/v1/user', 'GET', null, $token);
    if (($response['status'] ?? 0) !== 200 || !is_array($response['data'] ?? null)) {
        return false;
    }

    $user = $response['data'];
    $email = $user['email'] ?? '';
    $rol = 'alumno';
    if (strpos($email, '@udb.edu.sv') !== false && strpos($email, '@alumno.udb.edu.sv') === false) {
        $rol = 'admin';
    }

    $_SESSION['id'] = $user['id'] ?? null;
    $_SESSION['email'] = $email;
    $_SESSION['nombre'] = $user['user_metadata']['full_name'] ?? explode('@', $email)[0];
    $_SESSION['rol'] = $rol;
    $_SESSION['access_token'] = $token;

    return isset($_SESSION['id']);
}

function obtenerBearerToken() {
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $candidates = [
        $_SERVER['HTTP_AUTHORIZATION'] ?? null,
        $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? null,
        $headers['Authorization'] ?? null,
        $headers['authorization'] ?? null,
    ];

    foreach ($candidates as $candidate) {
        if (!$candidate) {
            continue;
        }

        if (preg_match('/Bearer\s+(.*)$/i', trim($candidate), $matches)) {
            return trim($matches[1]);
        }
    }

    return null;
}
