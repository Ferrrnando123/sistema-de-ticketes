<?php
// Controllers/TicketController.php

class TicketController {
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

    public function misTickets() {
        $userId = $_SESSION['id'];
        $response = Supabase::request("/rest/v1/tickets?user_id=eq.$userId&order=created_at.desc", 'GET', null, $_SESSION['access_token']);
        
        $tickets = ($response['status'] == 200) ? $response['data'] : [];
        require 'Views/mis-tickets.php';
    }

    public function panelSoporte() {
        // Obtener todos los tickets
        $response = Supabase::request("/rest/v1/tickets?order=created_at.desc", 'GET', null, $_SESSION['access_token']);
        $tickets = ($response['status'] == 200) ? $response['data'] : [];

        // Calcular estadísticas
        $stats = [
            'pendientes' => 0,
            'criticos' => 0,
            'resueltos_hoy' => 0
        ];

        $hoy = date('Y-m-d');
        foreach ($tickets as $t) {
            if ($t['estado'] != 'resuelto') $stats['pendientes']++;
            if ($t['prioridad'] == 'alta' && $t['estado'] != 'resuelto') $stats['criticos']++;
            if ($t['estado'] == 'resuelto' && strpos($t['updated_at'], $hoy) !== false) {
                $stats['resueltos_hoy']++;
            }
        }

        require 'Views/panelsoporte.php';
    }

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
