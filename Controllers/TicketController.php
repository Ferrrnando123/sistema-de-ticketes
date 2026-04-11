<?php
// Controllers/TicketController.php

class TicketController {
    // esta es la funcion para registrar un nuevo ticket
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $fotoUrl = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                // Subir archivo al bucket 'tickets' de Supabase Storage
                $tmpPath = $_FILES['foto']['tmp_name'];
                $mimeType = $_FILES['foto']['type'];
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('t_') . '.' . $ext;
                
                $uploadRes = Supabase::uploadFile('/storage/v1/object/tickets/' . $filename, $tmpPath, $mimeType, $_SESSION['access_token']);
                
                if ($uploadRes['status'] == 200 || $uploadRes['status'] == 201) {
                    $fotoUrl = SUPABASE_URL . '/storage/v1/object/public/tickets/' . $filename;
                }
            }

            $data = [
                'user_id' => $_SESSION['id'],
                'email' => $_SESSION['email'],
                'asunto' => $_POST['asunto'] ?? $input['asunto'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? $input['descripcion'] ?? '',
                'ubicacion' => $_POST['ubicacion'] ?? $input['ubicacion'] ?? '',
                'prioridad' => $_POST['prioridad'] ?? $input['prioridad'] ?? '',
                'foto_url' => $fotoUrl,
                'estado' => 'abierto'
            ];

            // aqui conectamos con la base de datos para insertar el ticket
            $response = Supabase::request('/rest/v1/tickets', 'POST', $data, $_SESSION['access_token']);

            if ($response['status'] == 201) {
                jsonResponse(201, true, 'Ticket creado exitosamente');
            } else {
                jsonResponse(500, false, 'Error al crear el ticket');
            }
        }
    }

    // esta es la funcion para listar los tickets que ha enviado el usuario
    public function misTickets() {
        $userId = $_SESSION['id'];
        // aqui realizamos la consulta filtrada por id de usuario
        $response = Supabase::request("/rest/v1/tickets?user_id=eq.$userId&order=created_at.desc", 'GET', null, $_SESSION['access_token']);
        
        $tickets = ($response['status'] == 200) ? $response['data'] : [];
        jsonResponse(200, true, 'Tickets obtenidos', $tickets);
    }

    // esta es la funcion para cargar las estadisticas y panel de administrador
    public function panelSoporte() {
        $token = $_SESSION['access_token'];
        $hoy = date('Y-m-d');
        $stats = [];

        // Helper para obtener el conteo desde los headers de Supabase (PostgREST)
        $getCount = function($query) use ($token) {
            $res = Supabase::request($query, 'GET', null, $token, [
                "Prefer: count=exact",
                "Range: 0-0"
            ]);
            
            if (isset($res['headers']['content-range'])) {
                // El formato es "0-0/total" o "*/total"
                $parts = explode('/', $res['headers']['content-range']);
                return (int) end($parts);
            }
            return 0;
        };

        // 1. Obtener conteos ultra-ligeros (solo headers, sin descargar filas)
        $stats['pendientes'] = $getCount("/rest/v1/tickets?estado=neq.resuelto");
        $stats['criticos'] = $getCount("/rest/v1/tickets?prioridad=eq.alta&estado=neq.resuelto");
        $stats['resueltos_hoy'] = $getCount("/rest/v1/tickets?estado=eq.resuelto&updated_at=gte.$hoy");

        // 2. Obtener solo los últimos 50 tickets para mostrar en la tabla (con datos)
        $response = Supabase::request("/rest/v1/tickets?select=*&order=created_at.desc&limit=50", 'GET', null, $token);
        $tickets = ($response['status'] == 200) ? $response['data'] : [];

        jsonResponse(200, true, 'Panel de soporte obtenido', [
            'stats' => $stats,
            'tickets' => $tickets
        ]);
    }

    // esta es la funcion para cambiar el estado de un ticket (Admin)
    public function actualizarEstado() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PATCH') {
            $input = json_decode(file_get_contents('php://input'), true) ?: [];
            $id = $_POST['id'] ?? $input['id'] ?? null;
            $nuevoEstado = $_POST['estado'] ?? $input['estado'] ?? null;

            if (!$id || !$nuevoEstado) {
                jsonResponse(400, false, 'Faltan parámetros id o estado. Recibido: ' . json_encode(['id' => $id, 'estado' => $nuevoEstado]));
            }

            $token = $_SESSION['access_token'] ?? null;
            if (!$token) {
                jsonResponse(401, false, 'No se encontró el token de acceso en la sesión.');
            }

            $response = Supabase::request("/rest/v1/tickets?id=eq." . (int)$id, 'PATCH', [
                'estado' => $nuevoEstado,
                'updated_at' => date('c')
            ], $token);

            if ($response['status'] == 200 || $response['status'] == 204) {
                jsonResponse(200, true, 'Estado actualizado');
            } else {
                jsonResponse(500, false, 'Error al actualizar el estado');
            }
        }
    }
}
