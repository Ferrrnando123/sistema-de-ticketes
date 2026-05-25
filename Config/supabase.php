<?php
// Config/supabase.php

$localSecrets = [];
$localSecretsPath = __DIR__ . DIRECTORY_SEPARATOR . 'secrets.local.php';
if (file_exists($localSecretsPath)) {
    $loaded = require $localSecretsPath;
    if (is_array($loaded)) {
        $localSecrets = $loaded;
    }
}

// aqui conectamos con la url de supabase
define('SUPABASE_URL', 'https://qhnwltdwxfewpolexydl.supabase.co');
// aqui realizamos la definicion del token de acceso
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InFobndsdGR3eGZld3BvbGV4eWRsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzE4MDYwNzUsImV4cCI6MjA4NzM4MjA3NX0.yOJEQy8V82cKxOe8Ho2FxLFlaDg-5Z1OoBSWV1UxxBg');
define(
    'SUPABASE_SERVICE_ROLE_KEY',
    getenv('SUPABASE_SERVICE_ROLE_KEY')
        ?: ($localSecrets['SUPABASE_SERVICE_ROLE_KEY'] ?? '')
);
define(
    'GEMINI_API_KEY',
    getenv('GEMINI_API_KEY')
        ?: ($localSecrets['GEMINI_API_KEY'] ?? '')
);
define(
    'GEMINI_MODEL',
    getenv('GEMINI_MODEL')
        ?: ($localSecrets['GEMINI_MODEL'] ?? 'gemini-2.5-flash')
);

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

    public static function requestAsService($endpoint, $method = 'GET', $data = null, $customHeaders = []) {
        if (!defined('SUPABASE_SERVICE_ROLE_KEY') || SUPABASE_SERVICE_ROLE_KEY === '') {
            return ['status' => 0, 'headers' => [], 'data' => null, 'error' => 'SERVICE_ROLE_NOT_CONFIGURED'];
        }
        $url = SUPABASE_URL . $endpoint;
        $ch = curl_init($url);

        $headers = [
            'apikey: ' . SUPABASE_SERVICE_ROLE_KEY,
            'Content-Type: application/json',
            'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY
        ];
        if (!empty($customHeaders)) {
            $headers = array_merge($headers, $customHeaders);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
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

        $parsedHeaders = [];
        foreach (explode("\r\n", $responseHeaders) as $line) {
            if (strpos($line, ':') !== false) {
                [$key, $value] = explode(':', $line, 2);
                $parsedHeaders[strtolower(trim($key))] = trim($value);
            }
        }

        return [
            'status' => $status,
            'headers' => $parsedHeaders,
            'data' => json_decode($body, true)
        ];
    }
}

class ContentFilter {
    private static $cachedTerms = null;

    private static function normalize($text) {
        $text = mb_strtolower((string)$text, 'UTF-8');
        $replace = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
            'ñ' => 'n'
        ];
        $text = strtr($text, $replace);
        $text = preg_replace('/[^a-z0-9\s]/u', ' ', $text);
        $text = preg_replace('/\s+/u', ' ', $text);
        return trim($text);
    }

    private static function defaultTerms() {
        return [
            'idiota', 'idiotas', 'imbecil', 'imbeciles', 'estupido', 'estupidos', 'pendejo', 'pendeja',
            'mierda', 'puta', 'puto', 'malparido', 'malparida', 'cerote', 'hijueputa', 'hijo de puta',
            'hija de puta', 'cabron', 'cabrona', 'verga', 'chingar', 'chingada', 'chingado', 'cojudo',
            'cojuda', 'culo', 'culero', 'culera', 'maricon', 'marica', 'zorra', 'zorro', 'prostituta',
            'perra', 'perro de mierda', 'basura humana', 'maten', 'matar', 'te odio', 'te voy a matar',
            'mamapinga', 'come mierda', 'comemierda', 'baboso', 'babosa', 'tarado', 'tarada', 'retrasado',
            'mongol', 'animal', 'inutil', 'inservible', 'asqueroso', 'asquerosa', 'hdp', 'ptm',
            'ctm', 'fck', 'fuck', 'shit', 'bitch', 'asshole', 'motherfucker'
        ];
    }

    public static function blockedWords() {
        if (is_array(self::$cachedTerms)) {
            return self::$cachedTerms;
        }

        $terms = self::defaultTerms();
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'blacklist_terms.json';

        if (file_exists($file)) {
            $json = json_decode(file_get_contents($file), true);
            if (is_array($json)) {
                foreach ($json as $categoryTerms) {
                    if (is_array($categoryTerms)) {
                        foreach ($categoryTerms as $term) {
                            $term = trim((string)$term);
                            if ($term !== '') {
                                $terms[] = $term;
                            }
                        }
                    }
                }
            }
        }

        $terms = array_values(array_unique($terms));
        self::$cachedTerms = $terms;
        return $terms;
    }

    public static function findBlockedTerm($text) {
        $normalized = self::normalize($text);
        if ($normalized === '') {
            return null;
        }

        foreach (self::blockedWords() as $term) {
            $t = self::normalize($term);
            if ($t === '') {
                continue;
            }

            if (str_contains($t, ' ')) {
                if (str_contains($normalized, $t)) {
                    return $term;
                }
            } else {
                if (preg_match('/\b' . preg_quote($t, '/') . '\b/u', $normalized)) {
                    return $term;
                }
            }
        }

        return null;
    }
}
