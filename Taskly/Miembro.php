<?php
// Archivo: Miembro.php
require_once 'Conexion.php';

class Miembro {
    // Propiedades de la clase (atributos)
    private $db;
    public $id;
    public $nombre;
    public $correo;
    public $rol;

    // --- 3. IMPLEMENTAR: CONSTRUCTOR ---
    // Recibe la conexión de la base de datos y opcionalmente los datos del miembro
    public function __construct($db, $nombre = null, $correo = null, $rol = null, $id = null) {
        $this->db = $db;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->rol = $rol;
        $this->id = $id;
    }

    // --- 3. IMPLEMENTAR: MÉTODOS (CRUD BÁSICO EN BD) ---

    // [C]REAR: Método para registrar un nuevo miembro (Usado por el Modal Añadir)
    public function registrar() {
        try {
            $query = "INSERT INTO users (nombre, email, rol, password) VALUES (:nombre, :correo, :rol, '')";            $stmt = $this->db->prepare($query);

            // Sanitizamos los datos por seguridad
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->correo = htmlspecialchars(strip_tags($this->correo));
            $this->rol = htmlspecialchars(strip_tags($this->rol));

            // Vinculamos los parámetros de PDO
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":correo", $this->correo);
            $stmt->bindParam(":rol", $this->rol);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error al registrar miembro: " . $e->getMessage();
            return false;
        }
    }

    // [R]EAD: Método estático o regular para obtener todos los miembros (Para llenar la tabla)
    public function obtenerTodos() {
        try {
            $query = "SELECT id, nombre, email AS correo, rol FROM users ORDER BY id DESC";            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error al consultar miembros: " . $e->getMessage();
            return [];
        }
    }

    // [U]PDATE: Método para actualizar el rol desde el menú desplegable (Select)
    public function actualizarRol($nuevoRol, $idUsuario) {
        try {
            $query = "UPDATE users SET rol = :rol WHERE id = :id";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":rol", $nuevoRol);
            $stmt->bindParam(":id", $idUsuario);

            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Error al actualizar rol: " . $e->getMessage();
            return false;
        }
    }

    // [D]ELETE: Método para eliminar un integrante de la base de datos
    public function eliminar($idUsuario) {
        try {
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $idUsuario);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Error al eliminar miembro: " . $e->getMessage();
            return false;
        }
    }
}
?>