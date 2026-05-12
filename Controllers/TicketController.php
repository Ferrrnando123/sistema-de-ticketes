<?php
// Controllers/TicketController.php

class TicketController {
    private function isAdmin() {
        return (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
    }

    private function normalizeText($text) {
        $text = (string)($text ?? '');
        $text = trim(mb_strtolower($text, 'UTF-8'));
        $text = preg_replace('/\s+/u', ' ', $text);
        return $text;
    }

    // Simulación lógica de "IA": moderación + prioridad basada en texto.
    // Retorna: ['blocked' => bool, 'reason' => string|null, 'priority' => 'baja'|'media'|'alta'|'critica']
    private function aiAnalyzeTicketText($asunto, $descripcion) {
        $text = $this->normalizeText($asunto . ' ' . $descripcion);

        $banned = [
            'idiota', 'imbecil', 'imbécil', 'estupido', 'estúpido', 'pendejo', 'pendeja',
            'mierda', 'puta', 'puto', 'malparido', 'cerote', 'hijueputa', 'hijo de puta',
            'tonto', 'basura', 'cagada'
        ];

        foreach ($banned as $w) {
            if (mb_strpos($text, $w) !== false) {
                return [
                    'blocked' => true,
                    'reason' => 'Contenido ofensivo detectado. Por favor redacta el ticket con respeto.',
                    'priority' => 'media'
                ];
            }
        }

        $plain = preg_replace('/[^a-z0-9áéíóúñ ]/iu', '', $text);
        $words = array_values(array_filter(explode(' ', $plain), fn($t) => $t !== ''));
        if (mb_strlen($text, 'UTF-8') < 12 || count($words) < 3) {
            return [
                'blocked' => true,
                'reason' => 'Descripción muy corta o poco clara. Agrega más detalle para poder ayudarte.',
                'priority' => 'baja'
            ];
        }
        if (preg_match('/(jaja|jeje|xd|lol){2,}/iu', $text) || preg_match('/(.)\\1{6,}/u', $text)) {
            return [
                'blocked' => true,
                'reason' => 'El contenido parece no serio o con spam. Por favor detalla el problema de forma clara.',
                'priority' => 'baja'
            ];
        }
        if (preg_match('/\\b(test|prueba|asdf|qwer)\\b/iu', $text)) {
            return [
                'blocked' => true,
                'reason' => 'El ticket parece de prueba. Si es un incidente real, describe el problema y su impacto.',
                'priority' => 'baja'
            ];
        }

        $critica = [
            'incendio', 'fuga', 'electrico', 'eléctrico', 'corto circuito', 'descarga', 'seguridad',
            'emergencia', 'caído', 'caida', 'caída', 'servidor caido', 'servidor caído',
            'sin internet', 'internet caido', 'internet caído', 'no hay internet', 'bloqueante',
            'no puedo acceder', 'no puedo entrar'
        ];
        $alta = [
            'urgente', 'inmediato', 'hoy', 'clase', 'examen', 'evaluación', 'evaluacion',
            'no funciona', 'no enciende', 'error 500', 'no imprime', 'impresora atorada',
            'red lenta', 'wifi no', 'no hay wifi', 'sin wifi'
        ];
        $media = [
            'intermitente', 'lento', 'lentitud', 'fallando', 'inestable', 'actualizar',
            'actualización', 'configurar', 'configuración', 'ayuda'
        ];

        $priority = 'baja';
        foreach ($critica as $k) {
            if (mb_strpos($text, $k) !== false) {
                $priority = 'critica';
                break;
            }
        }
        if ($priority !== 'critica') {
            foreach ($alta as $k) {
                if (mb_strpos($text, $k) !== false) {
                    $priority = 'alta';
                    break;
                }
            }
        }
        if ($priority === 'baja') {
            foreach ($media as $k) {
                if (mb_strpos($text, $k) !== false) {
                    $priority = 'media';
                    break;
                }
            }
        }

        return ['blocked' => false, 'reason' => null, 'priority' => $priority];
    }

    // esta es la funcion para registrar un nuevo ticket
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            jsonResponse(405, false, 'Método no permitido.');
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $fotoUrl = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
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
            'prioridad' => null,
            'foto_url' => $fotoUrl,
            'estado' => 'abierto'
        ];

        if (empty($data['asunto']) || empty($data['descripcion']) || empty($data['ubicacion'])) {
            jsonResponse(400, false, 'Asunto, descripción y ubicación son requeridos.');
        }

        $analysis = $this->aiAnalyzeTicketText($data['asunto'], $data['descripcion']);
        if (!empty($analysis['blocked'])) {
            jsonResponse(422, false, $analysis['reason'] ?? 'Contenido no permitido.');
        }
        $data['prioridad'] = $analysis['priority'] ?? 'media';

        $response = Supabase::request('/rest/v1/tickets', 'POST', $data, $_SESSION['access_token']);

        if ($response['status'] == 201) {
            jsonResponse(201, true, 'Ticket creado exitosamente', [
                'prioridad' => $data['prioridad']
            ]);
        }

        jsonResponse(500, false, 'Error al crear el ticket', $response['data'] ?? null);
    }

    // esta es la funcion para listar los tickets que ha enviado el usuario
    public function misTickets() {
        $userId = $_SESSION['id'];
        $response = Supabase::request("/rest/v1/tickets?user_id=eq.$userId&order=created_at.desc", 'GET', null, $_SESSION['access_token']);
        $tickets = ($response['status'] == 200) ? ($response['data'] ?? []) : [];
        jsonResponse(200, true, 'Tickets obtenidos', $tickets);
    }

    // Endpoint para el carrusel de actividad reciente (últimos 7)
    public function recentTickets() {
        $token = $_SESSION['access_token'];
        $limit = 7;
        if ($this->isAdmin()) {
            $response = Supabase::request("/rest/v1/tickets?select=id,asunto,email,created_at,prioridad,estado&order=created_at.desc&limit=$limit", 'GET', null, $token);
        } else {
            $userId = $_SESSION['id'];
            $response = Supabase::request("/rest/v1/tickets?select=id,asunto,email,created_at,prioridad,estado&user_id=eq.$userId&order=created_at.desc&limit=$limit", 'GET', null, $token);
        }
        $tickets = ($response['status'] == 200) ? ($response['data'] ?? []) : [];
        jsonResponse(200, true, 'Tickets recientes', $tickets);
    }

    // Detalle de ticket (para vista + chat)
    public function detalle() {
        $id = $_GET['id'] ?? null;
        $id = is_numeric($id) ? (int)$id : null;
        if (!$id) {
            jsonResponse(400, false, 'Parámetro id requerido.');
        }

        $token = $_SESSION['access_token'];
        $response = Supabase::request("/rest/v1/tickets?select=*&id=eq.$id&limit=1", 'GET', null, $token);
        if ($response['status'] != 200 || empty($response['data'][0])) {
            jsonResponse(404, false, 'Ticket no encontrado o sin permisos.');
        }

        jsonResponse(200, true, 'Detalle de ticket', $response['data'][0]);
    }

    // FAQ inteligente: búsqueda reactiva por asunto
    public function buscarFaq() {
        $q = $_GET['q'] ?? '';
        $q = trim($q);
        if (mb_strlen($q, 'UTF-8') < 3) {
            jsonResponse(200, true, 'Sugerencias', []);
        }

        $token = $_SESSION['access_token'];
        $safe = str_replace(['%'], ['\\%'], $q);
        $encoded = rawurlencode($safe);
        $endpoint = "/rest/v1/base_conocimientos?select=id,titulo,solucion,url&activo=eq.true&or=(titulo.ilike.*$encoded*,asunto_match.ilike.*$encoded*)&limit=6";
        $response = Supabase::request($endpoint, 'GET', null, $token);
        $rows = ($response['status'] == 200) ? ($response['data'] ?? []) : [];
        jsonResponse(200, true, 'Sugerencias', $rows);
    }

    private function assertTicketAccess($ticketId) {
        $ticketId = (int)$ticketId;
        $token = $_SESSION['access_token'];
        $res = Supabase::request("/rest/v1/tickets?select=id,user_id&id=eq.$ticketId&limit=1", 'GET', null, $token);
        if ($res['status'] != 200 || empty($res['data'][0])) {
            jsonResponse(404, false, 'Ticket no encontrado o sin permisos.');
        }
        $ticket = $res['data'][0];
        if (!$this->isAdmin() && $ticket['user_id'] !== $_SESSION['id']) {
            jsonResponse(403, false, 'Acceso denegado.');
        }
        return $ticket;
    }

    // Chat: listar mensajes por ticket
    public function listarMensajesTicket() {
        $ticketId = $_GET['ticket_id'] ?? null;
        $ticketId = is_numeric($ticketId) ? (int)$ticketId : null;
        if (!$ticketId) {
            jsonResponse(400, false, 'Parámetro ticket_id requerido.');
        }
        $this->assertTicketAccess($ticketId);

        $token = $_SESSION['access_token'];
        $response = Supabase::request("/rest/v1/mensajes_ticket?select=*&ticket_id=eq.$ticketId&order=created_at.asc&limit=200", 'GET', null, $token);
        $msgs = ($response['status'] == 200) ? ($response['data'] ?? []) : [];
        jsonResponse(200, true, 'Mensajes obtenidos', $msgs);
    }

    // Chat: enviar mensaje
    public function enviarMensajeTicket() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonResponse(405, false, 'Método no permitido.');
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $ticketId = $input['ticket_id'] ?? null;
        $mensaje = trim((string)($input['mensaje'] ?? ''));
        $ticketId = is_numeric($ticketId) ? (int)$ticketId : null;

        if (!$ticketId || $mensaje === '') {
            jsonResponse(400, false, 'ticket_id y mensaje son requeridos.');
        }
        if (mb_strlen($mensaje, 'UTF-8') > 2000) {
            jsonResponse(400, false, 'Mensaje demasiado largo.');
        }

        $this->assertTicketAccess($ticketId);

        $token = $_SESSION['access_token'];
        $payload = [
            'ticket_id' => $ticketId,
            'autor_user_id' => $_SESSION['id'],
            'autor_rol' => $this->isAdmin() ? 'admin' : 'cliente',
            'mensaje' => $mensaje
        ];

        $response = Supabase::request("/rest/v1/mensajes_ticket", 'POST', $payload, $token);
        if ($response['status'] == 201) {
            jsonResponse(201, true, 'Mensaje enviado');
        }
        jsonResponse(500, false, 'Error al enviar mensaje', $response['data'] ?? null);
    }

    // Notificaciones: listar (para polling UI)
    public function listarNotificaciones() {
        $token = $_SESSION['access_token'];
        $userId = $_SESSION['id'];
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? min(50, (int)$_GET['limit']) : 20;
        $onlyUnread = (isset($_GET['unread']) && $_GET['unread'] === '1');

        $query = "/rest/v1/notificaciones?select=*&user_id=eq.$userId&order=created_at.desc&limit=$limit";
        if ($onlyUnread) {
            $query .= "&leida=eq.false";
        }

        $response = Supabase::request($query, 'GET', null, $token);
        $rows = ($response['status'] == 200) ? ($response['data'] ?? []) : [];
        jsonResponse(200, true, 'Notificaciones', $rows);
    }

    public function marcarNotificacionLeida() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonResponse(405, false, 'Método no permitido.');
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = $input['id'] ?? ($_POST['id'] ?? null);
        $id = is_numeric($id) ? (int)$id : null;
        if (!$id) {
            jsonResponse(400, false, 'Parámetro id requerido.');
        }

        $token = $_SESSION['access_token'];
        $userId = $_SESSION['id'];
        $response = Supabase::request("/rest/v1/notificaciones?id=eq.$id&user_id=eq.$userId", 'PATCH', [
            'leida' => true
        ], $token);

        if ($response['status'] == 200 || $response['status'] == 204) {
            jsonResponse(200, true, 'Notificación marcada como leída');
        }
        jsonResponse(500, false, 'Error al marcar notificación', $response['data'] ?? null);
    }

    // esta es la funcion para cargar las estadisticas y panel de administrador
    public function panelSoporte() {
        $token = $_SESSION['access_token'];
        $hoy = date('Y-m-d');
        $stats = [];

        $getCount = function($query) use ($token) {
            $res = Supabase::request($query, 'GET', null, $token, [
                "Prefer: count=exact",
                "Range: 0-0"
            ]);

            if (isset($res['headers']['content-range'])) {
                $parts = explode('/', $res['headers']['content-range']);
                return (int) end($parts);
            }
            return 0;
        };

        $stats['pendientes'] = $getCount("/rest/v1/tickets?estado=neq.resuelto");
        $stats['criticos'] = $getCount("/rest/v1/tickets?or=(prioridad.eq.alta,prioridad.eq.critica)&estado=neq.resuelto");
        $stats['resueltos_hoy'] = $getCount("/rest/v1/tickets?estado=eq.resuelto&updated_at=gte.$hoy");

        $stats['estado_abierto'] = $getCount("/rest/v1/tickets?estado=eq.abierto");
        $stats['estado_en_proceso'] = $getCount("/rest/v1/tickets?estado=eq.en_proceso");
        $stats['estado_resuelto'] = $getCount("/rest/v1/tickets?estado=eq.resuelto");

        $catRes = Supabase::request("/rest/v1/tickets?select=ubicacion&order=created_at.desc&limit=1000", 'GET', null, $token);
        $counts = [];
        if ($catRes['status'] == 200 && is_array($catRes['data'])) {
            foreach ($catRes['data'] as $row) {
                $k = trim((string)($row['ubicacion'] ?? ''));
                if ($k === '') continue;
                $counts[$k] = ($counts[$k] ?? 0) + 1;
            }
        }
        arsort($counts);
        $topCats = [];
        foreach (array_slice($counts, 0, 8, true) as $k => $v) {
            $topCats[] = ['categoria' => $k, 'count' => $v];
        }

        $response = Supabase::request("/rest/v1/tickets?select=*&order=created_at.desc&limit=50", 'GET', null, $token);
        $tickets = ($response['status'] == 200) ? ($response['data'] ?? []) : [];

        jsonResponse(200, true, 'Panel de soporte obtenido', [
            'stats' => $stats,
            'charts' => [
                'estado' => [
                    ['name' => 'Abierto', 'value' => $stats['estado_abierto'] ?? 0],
                    ['name' => 'En Proceso', 'value' => $stats['estado_en_proceso'] ?? 0],
                    ['name' => 'Resuelto', 'value' => $stats['estado_resuelto'] ?? 0]
                ],
                'categorias' => $topCats
            ],
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
                jsonResponse(400, false, 'Faltan parámetros id o estado.', ['id' => $id, 'estado' => $nuevoEstado]);
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
            }
            jsonResponse(500, false, 'Error al actualizar el estado', $response['data'] ?? null);
        }

        jsonResponse(405, false, 'Método no permitido.');
    }
}

