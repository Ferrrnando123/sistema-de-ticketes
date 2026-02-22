// Dentro de la clase Alumno en models/Alumno.php
public function registrar($nombre, $email, $carnet, $pass) {
    try {
        $sql = "INSERT INTO alumnos (nombre, email, carnet, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $email, $carnet, $pass]);
    } catch (PDOException $e) {
        return false;
    }
}