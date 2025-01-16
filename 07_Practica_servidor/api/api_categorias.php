<?php 
    error_reporting( E_ALL );
    ini_set( "display_errors", 1 );

    header("Content-type: application/json");
    include("conexion_pdo.php");

    $metodo = $_SERVER["REQUEST_METHOD"];
    $entrada = json_decode(file_get_contents("php://input"), true);

    switch($metodo){
        case "GET":
            manejarGet($_conexion);
            break;
        case "POST":
            manejarPost($_conexion, $entrada);
            break;
        case "PUT":
            manejarPut($_conexion, $entrada);
            break;
        case "DELETE":
            manejarDelete($_conexion, $entrada);
        default:
            echo json_encode(["mensaje" => "Petición no válida"]);
    }

    function manejarGet($_conexion){
        $sql = "SELECT * FROM categorias";

        $stmt = $_conexion -> prepare($sql);
        $stmt -> execute();
        $resultado = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        echo json_decode($resultado);
    }

    function manejarPost($_conexion, $entrada){
        $sql = "INSERT INTO categorias (nombre, descripcion) 
            VALUES (:nombre, :descripcion)";
        $stmt = $_conexion -> prepare($sql);
        $stmt -> execute([
            "nombre" => $entrada["nombre"],
            "descripcion" => $entrada["descripcion"]
        ]);
        if($stmt){
            echo json_encode(["mensaje" => "La categoría se ha creado"]);
        } else {
            echo json_encode(["mensaje" => "Error al crear la categoría"]);
        }

    }

    function manejarPut($_conexion, $entrada){
        $sql = "UPDATE categorias SET
            nombre = :nombre,
            descripcion = :descripcion";
        $stmt = $_conexion -> prepare($sql);
        $stmt -> execute([
            "nombre" => $entrada["nombre"],
            "descripcion" => $entrda["descripcion"]
        ]);
        if($stmt){
            echo json_encode(["mensaje" => "La categoría ha sido modificada"]);
        } else{
            echo json_encode(["mensaje" => "Error al modificar la categoría"]);
        }
    }

    function manejarDelete($_conexion, $entrada){
        $sql = "DELETE FROM categorias WHERE nombre = :nombre";
        $stmt = $_conexion -> prepare($sql);
        $stmt -> execute([
            "nombre" => $entrada["nombre"]
        ]);
        if($stmt){
            echo json_encode(["mensaje" => "La categoría ha sido eliminada"]);
        } else{
            echo json_encode(["mensaje" => "Error al eliminar la categoría"]);
        }
    }
?>