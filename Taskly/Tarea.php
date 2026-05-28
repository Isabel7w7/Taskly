<?php
// Archivo: Tarea.php

require_once 'Conexion.php';

class Tarea {

    private $db;

    public $id;
    public $usuario_id;
    public $descripcion;
    public $estatus;
    public $fecha_entrega;

    // Constructor
    public function __construct(
        $db,
        $descripcion = null,
        $usuario_id = null,
        $estatus = 'Por hacer',
        $fecha_entrega = null,
        $id = null
    ) {

        $this->db = $db;
        $this->descripcion = $descripcion;
        $this->usuario_id = $usuario_id;
        $this->estatus = $estatus;
        $this->fecha_entrega = $fecha_entrega;
        $this->id = $id;
    }

    // =====================================================
    // CREATE
    // =====================================================
    public function registrar() {

        try {

            $query = "INSERT INTO tareas
            (usuario_id, descripcion, estatus, fecha_entrega)

            VALUES
            (:usuario_id, :descripcion, :estatus, :fecha_entrega)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':usuario_id', $this->usuario_id);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':estatus', $this->estatus);
            $stmt->bindParam(':fecha_entrega', $this->fecha_entrega);

            return $stmt->execute();

        } catch(PDOException $e) {

            echo "Error al registrar tarea: " . $e->getMessage();
            return false;

        }
    }

    // =====================================================
    // READ - TODAS LAS TAREAS
    // =====================================================
    public function obtenerTodas() {

        try {

            $query = "SELECT
                        t.id,
                        t.descripcion,
                        t.estatus,
                        t.fecha_entrega,
                        t.usuario_id,

                        u.nombre AS encargado_nombre,
                        u.rol AS encargado_rol

                      FROM tareas t

                      LEFT JOIN users u
                      ON t.usuario_id = u.id

                      ORDER BY t.id DESC";

            $stmt = $this->db->prepare($query);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {

            echo "Error al obtener tareas: " . $e->getMessage();
            return [];

        }
    }

    // =====================================================
    // READ - TAREAS POR USUARIO
    // =====================================================
    public function obtenerPorUsuario($usuario_id) {

        try {

            $query = "SELECT *
                      FROM tareas
                      WHERE usuario_id = :usuario_id
                      ORDER BY id DESC";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':usuario_id', $usuario_id);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {

            echo "Error al obtener tareas del usuario: "
                 . $e->getMessage();

            return [];

        }
    }

    // =====================================================
    // UPDATE
    // =====================================================
    public function actualizar(
        $id,
        $descripcion,
        $usuario_id,
        $estatus,
        $fecha_entrega
    ) {

        try {

            $query = "UPDATE tareas

                      SET
                        descripcion = :descripcion,
                        usuario_id = :usuario_id,
                        estatus = :estatus,
                        fecha_entrega = :fecha_entrega

                      WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':estatus', $estatus);
            $stmt->bindParam(':fecha_entrega', $fecha_entrega);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();

        } catch(PDOException $e) {

            echo "Error al actualizar tarea: "
                 . $e->getMessage();

            return false;

        }
    }

    // =====================================================
    // DELETE
    // =====================================================
    public function eliminar($id) {

        try {

            $query = "DELETE FROM tareas
                      WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id);

            return $stmt->execute();

        } catch(PDOException $e) {

            echo "Error al eliminar tarea: "
                 . $e->getMessage();

            return false;

        }
    }
}
?>