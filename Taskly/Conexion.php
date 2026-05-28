<?php
// Archivo: Conexion.php

class Conexion {
    private $host = "localhost";
    private $db_name = "taskly_db";
    private $username = "root";
    private $password = "konosuba890*"; // Como entraste sin contraseña en Workbench, déjalo vacío "konosuba890*"
    public $conn;

    // Método para conectar a la base de datos usando PDO
    public function conectar() {
        $this->conn = null;

        try {
            // Creamos la conexión especificada con UTF-8 para evitar problemas con acentos o eñes
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            
            // Configurar PDO para que lance excepciones en caso de errores de SQL
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            echo "Error de conexión en Taskly: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>