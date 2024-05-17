<?php 
session_start();
include("../funcs/conexion.php");

class LibroManager {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function ejecutarQuery($query, $param_types, $params) {
        $informacion = [];

        if(!$this->usuario_autentificado()){
            $informacion["respuesta"] = "ERROR";
            $informacion["error"] = "Usuario no autentificado";
            return $informacion;
        }

        $stmt = $this->mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param($param_types, ...$params);
            if ($stmt->execute()) {
                $informacion["respuesta"] = "BIEN";
            } else {
                $informacion["respuesta"] = "ERROR";
                $informacion["error"] = htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            $informacion["respuesta"] = "ERROR";
            $informacion["error"] = htmlspecialchars($this->mysqli->error);
        }
        return $informacion;
    }

    public function usuario_autentificado(){
        // Aqui hariamos un sistema de autentificacion mas robusto... 
        // Para la demo nos sirve
        return isset($_SESSION["id_usuario"]);
            
    }

    public function agregar_libro($titulo, $autor, $genero, $tematica) {
        $query = "INSERT INTO libros (titulo, autor, genero, tematica) VALUES (?, ?, ?, ?)";
        $params = [$titulo, $autor, $genero, $tematica];
        $param_types = "ssss";
        $informacion = $this->ejecutarQuery($query, $param_types, $params);
        echo json_encode($informacion);
    }

    public function modificar_libro($idlibro, $titulo, $autor, $genero, $tematica) {
        $query = "UPDATE libros SET titulo=?, autor=?, genero=?, tematica=? WHERE id=?";
        $params = [$titulo, $autor, $genero, $tematica, $idlibro];
        $param_types = "ssssi";
        $informacion = $this->ejecutarQuery($query, $param_types, $params);
        echo json_encode($informacion);
    }

    public function eliminar_libro($idlibro) {
        $query = "DELETE FROM libros WHERE id=?";
        $params = [$idlibro];
        $param_types = "i";
        $informacion = $this->ejecutarQuery($query, $param_types, $params);
        echo json_encode($informacion);
    }

    public function __destruct() {
        $this->mysqli->close();
    }
}

// Creamos una instancia de LibroManager
$libroManager = new LibroManager($mysqli);

$idlibro = isset($_POST['idlibro']) ? $_POST['idlibro'] : null; 
$opcion = $_POST["opcion"];
$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : null;
$autor = isset($_POST['autor']) ? $_POST['autor'] : null;
$genero = isset($_POST['genero']) ? $_POST['genero'] : null;
$tematica = isset($_POST['tematica']) ? $_POST['tematica'] : null;

switch ($opcion) {
    case 'agregar':
        $libroManager->agregar_libro($titulo, $autor, $genero, $tematica);
        break;
    case 'modificar':
        $libroManager->modificar_libro($idlibro, $titulo, $autor, $genero, $tematica);
        break;
    case 'eliminar':
        $libroManager->eliminar_libro($idlibro);
        break;
}

?>
