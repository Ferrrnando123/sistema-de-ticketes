<?php
// config/db.php

// Datos de ejemplo (Tus compañeros pondrán los reales aquí)
$host     = "localhost"; 
$port     = "5432";
$db_name  = "postgres";
$user     = "postgres";
$password = "password";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db_name";
    
    // Intentamos conectar, pero si falla no matamos el programa
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // En lugar de die(), dejamos $pdo como null para que el index no muera
    $pdo = null; 
    // Esto solo saldrá en la consola del servidor, no arruinará tu vista
    error_log("Base de datos no conectada aún, pa.");
}
?>