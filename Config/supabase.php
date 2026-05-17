<?php
// Config/supabase.php

// aqui conectamos con la url de supabase
define('SUPABASE_URL', 'https://qhnwltdwxfewpolexydl.supabase.co');
// aqui realizamos la definicion del token de acceso
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InFobndsdGR3eGZld3BvbGV4eWRsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzE4MDYwNzUsImV4cCI6MjA4NzM4MjA3NX0.yOJEQy8V82cKxOe8Ho2FxLFlaDg-5Z1OoBSWV1UxxBg');
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: 'AIzaSyANjXvp3M2BFvtgzF7zgRVElBD1dWQsxkA');
define('GEMINI_MODEL', getenv('GEMINI_MODEL') ?: 'gemini-1.5-flash');

// esta es la clase para manejar la api
class Supabase {
    // esta es la funcion para hacer peticiones curl a supabase
    public static function request($endpoint, $method = 'GET', $data = null, $token = null, $customHeaders = []) {
        $url = SUPABASE_URL . $endpoint;
        $ch = curl_init($url);
        
        $headers = [
            'apikey: ' . SUPABASE_KEY,
            'Content-Type: application/json'
        ];

        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        } else {
            $headers[] = 'Authorization: Bearer ' . SUPABASE_KEY;
        }

        if (!empty($customHeaders)) {
            $headers = array_merge($headers, $customHeaders);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true); // Retornar cabeceras en el output
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        $responseHeaders = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        curl_close($ch);

        // Convertir headers a un array asociativo
        $parsedHeaders = [];
        foreach (explode("\r\n", $responseHeaders) as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $parsedHeaders[strtolower(trim($key))] = trim($value);
            }
        }

        return [
            'status' => $status,
            'headers' => $parsedHeaders,
            'data' => json_decode($body, true)
        ];
    }

    // esta es la funcion para subir archivos (fotos) a Supabase Storage
    public static function uploadFile($endpoint, $filePath, $mimeType, $token = null) {
        $url = SUPABASE_URL . $endpoint;
        $ch = curl_init($url);
        
        $headers = [
            'apikey: ' . SUPABASE_KEY,
            'Content-Type: ' . $mimeType
        ];

        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        } else {
            $headers[] = 'Authorization: Bearer ' . SUPABASE_KEY;
        }

        $fileData = file_get_contents($filePath);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $status,
            'data' => json_decode($response, true)
        ];
    }
}

class Gemini {
    public static function generateJson($prompt, $temperature = 0.2) {
        if (!defined('GEMINI_API_KEY') || empty(GEMINI_API_KEY)) {
            return ['ok' => false, 'error' => 'GEMINI_API_KEY no configurada', 'data' => null];
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . GEMINI_MODEL . ':generateContent?key=' . GEMINI_API_KEY;

        $payload = [
            'contents' => [[
                'role' => 'user',
                'parts' => [['text' => $prompt]]
            ]],
            'generationConfig' => [
                'temperature' => $temperature,
                'responseMimeType' => 'application/json'
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 18);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $raw = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status < 200 || $status >= 300 || !$raw) {
            return ['ok' => false, 'error' => 'Error HTTP Gemini: ' . $status, 'data' => null];
        }

        $decoded = json_decode($raw, true);
        $text = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!$text) {
            return ['ok' => false, 'error' => 'Respuesta vacía de Gemini', 'data' => $decoded];
        }

        $json = json_decode($text, true);
        if (!is_array($json)) {
            return ['ok' => false, 'error' => 'Gemini no devolvió JSON válido', 'data' => $text];
        }

        return ['ok' => true, 'error' => null, 'data' => $json];
    }
}
