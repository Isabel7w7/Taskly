<?php
// Archivo: Usuario.php
require_once __DIR__ . '/Conexion.php'; // Enlaza el puente a la base de datos

class Usuario {
    private $id;
    private $nombre;
    private $username;
    private $email;
    private $password;
    private $rol;

    // Constructor para inicializar el objeto
    public function __construct($id, $nombre, $username, $email, $password, $rol = 'usuario') {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    // Métodos Getters (Encapsulamiento)
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRol() { return $this->rol; }

    // --- MÉTODO CON POO Y BASE DE DATOS REAL ---
    public static function buscarPorEmail($email) {
        try {
            // 1. Instanciar la clase de conexión y obtener el objeto PDO
            $database = new Conexion();
            $db = $database->conectar();

            // 2. Preparar la consulta SQL (Sentencia preparada contra inyecciones SQL)
            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $db->prepare($query);

            // 3. Limpiar el correo de espacios o caracteres extraños
            $email = htmlspecialchars(strip_tags(trim($email)));
            $stmt->bindParam(':email', $email);

            // 4. Ejecutar la consulta en MySQL
            $stmt->execute();

            // 5. Si encontramos una fila coincidente
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Retornamos el Objeto construido con los datos reales de la BD
                return new Usuario(
                    $row['id'],
                    $row['nombre'],
                    $row['username'],
                    $row['email'],
                    $row['password'],
                    $row['rol']
                );
            }
        } catch (PDOException $e) {
            // Si hay un error de SQL, el sistema no se rompe y devuelve null
            return null;
        }
        return null;
    }
}
?>