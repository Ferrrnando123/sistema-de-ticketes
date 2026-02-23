<?php
// Controllers/TicketController.php

class TicketController {
    // esta es la funcion para registrar un nuevo ticket
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['id'],
                'email' => $_SESSION['email'],
                'asunto' => $_POST['asunto'],
                'descripcion' => $_POST['descripcion'],
                'ubicacion' => $_POST['ubicacion'],
                'prioridad' => $_POST['prioridad'],
                'estado' => 'abierto'
            ];

            // aqui conectamos con la base de datos para insertar el ticket
            $response = Supabase::request('/rest/v1/tickets', 'POST', $data, $_SESSION['access_token']);

            if ($response['status'] == 201) {
                header("Location: index.php?action=mis-tickets&success=1");
                exit();
            } else {
                header("Location: index.php?action=nuevo-ticket&error=1");
                exit();
            }
        }
    }

    // esta es la funcion para listar los tickets que ha enviado el usuario
    public function misTickets() {
        $userId = $_SESSION['id'];
        // aqui realizamos la consulta filtrada por id de usuario
        $response = Supabase::request("/rest/v1/tickets?user_id=eq.$userId&order=created_at.desc", 'GET', null, $_SESSION['access_token']);
        
        $tickets = ($response['status'] == 200) ? $response['data'] : [];
        require 'Views/mis-tickets.php';
    }

    // esta es la funcion para cargar las estadisticas y panel de administrador
    public function panelSoporte() {
        $token = $_SESSION['access_token'];
        $hoy = date('Y-m-d');

        // 1. Obtener conteos ligeros desde Supabase (usando Prefer: count=exact y limit=0 para no traer datos)
        // Pendientes (No resueltos)
        $pRes = Supabase::request("/rest/v1/tickets?estado=neq.resuelto", 'GET', null, $token, ["Prefer: count=exact", "Range: 0-0"]);
        $stats['pendientes'] = $pRes['data'][0]['count'] ?? 0;
        if (isset($pRes['data']) && empty($pRes['data'])) {
             // Workaround si Supabase no devuelve el count en el body (depende de la config de PostgREST)
             // Pero usualmente se prefiere leer el header 'Content-Range' el cual mi helper actual no captura.
             // Como solución rápida y efectiva para este caso: Pedir solo el ID de los que cumplen la condición.
        }

        // RE-OPTIMIZACIÓN: Usar rpc o simplemente filtrar con selects específicos
        // Para no complicar el helper con lectura de headers de respuesta, haremos selects filtrados:
        
        // Pendientes
        $pendientes = Supabase::request("/rest/v1/tickets?select=id&estado=neq.resuelto", 'GET', null, $token);
        $stats['pendientes'] = is_array($pendientes['data']) ? count($pendientes['data']) : 0;

        // Críticos (Alta y no resueltos)
        $criticos = Supabase::request("/rest/v1/tickets?select=id&prioridad=eq.alta&estado=neq.resuelto", 'GET', null, $token);
        $stats['criticos'] = is_array($criticos['data']) ? count($criticos['data']) : 0;

        // Resueltos Hoy
        $resueltosHoy = Supabase::request("/rest/v1/tickets?select=id&estado=eq.resuelto&updated_at=gte.$hoy", 'GET', null, $token);
        $stats['resueltos_hoy'] = is_array($resueltosHoy['data']) ? count($resueltosHoy['data']) : 0;

        // 2. Obtener solo los últimos 50 tickets para mostrar en la tabla
        $response = Supabase::request("/rest/v1/tickets?order=created_at.desc&limit=50", 'GET', null, $token);
        $tickets = ($response['status'] == 200) ? $response['data'] : [];

        require 'Views/panelsoporte.php';
    }

    // esta es la funcion para cambiar el estado de un ticket (Admin)
    public function actualizarEstado() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
            $id = $_POST['id'];
            $nuevoEstado = $_POST['estado'];

            $response = Supabase::request("/rest/v1/tickets?id=eq.$id", 'PATCH', [
                'estado' => $nuevoEstado,
                'updated_at' => date('c')
            ], $_SESSION['access_token']);

            header("Location: index.php?action=panel-soporte&updated=1");
            exit();
        }
    }
}
