<?php
// Config/supabase.php

// aqui conectamos con la url de supabase
define('SUPABASE_URL', 'https://qhnwltdwxfewpolexydl.supabase.co');
// aqui realizamos la definicion del token de acceso
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InFobndsdGR3eGZld3BvbGV4eWRsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzE4MDYwNzUsImV4cCI6MjA4NzM4MjA3NX0.yOJEQy8V82cKxOe8Ho2FxLFlaDg-5Z1OoBSWV1UxxBg');

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
        curl_setopt($ch, CURLOPT_HEADER, false);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $status,
            'data' => json_decode($response, true)
        ];
    }
}
