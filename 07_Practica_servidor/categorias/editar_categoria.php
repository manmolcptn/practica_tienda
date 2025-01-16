<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1 ); 
        
        require('../util/conexion.php');

        //Para recuperar la sesion de otro fichero
        session_start();
        //Si no hay una sesion creada del usuario lo mando a iniciar sesión
        if(!isset($_SESSION["usuario"])) {
            header("location: ../usuario/iniciar_sesion.php");
            exit;
        }
    ?>
</head>
<body>
    <div class="container">
        <?php
            $sql = "SELECT * FROM categorias ORDER BY nombre";
            $resultado = $_conexion -> query($sql);
            $categorias = [];

            //Introduce las categorias en un nuevo array
            while($fila = $resultado -> fetch_assoc()){
                array_push($categorias, $fila["nombre"]);

            }

            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $nombre = $_POST["nombre"];
                $tmp_descripcion = $_POST["descripcion"];

                if($tmp_descripcion == "") $err_descripcion = "La descripción es obligatoria.";
                else{
                    if(strlen($tmp_descripcion) > 255) $err_descripcion = "Se permiten 255 caractéres como máximo.";
                    else{
                        $patron = "/^[a-zA-Z0-9ÁÉÍÓÚÑáéíóúñ ]+$/";
                        if(!preg_match($patron, $tmp_descripcion)) $err_descripcion = "Sólo se permiten letras, números y espacios en blanco.";
                        else $descripcion = $tmp_descripcion;
                    }
                }

                if(isset($descripcion)){
                    $sql = $_conexion -> prepare("UPDATE categorias SET descripcion = ? WHERE nombre = ?");
                    $sql -> bind_param("ss", $descripcion, $nombre);
                    $sql -> execute();
                    echo "<div class='container alert alert-success mt-3'>La categoría $nombre ha sido moficada correctamente!!</div>";
                }
                
            }
            $nombre = $_GET["nombre"];
            echo "<h3>Categoría $nombre</h3>";
            $sql = $_conexion -> prepare("SELECT * FROM categorias WHERE nombre = ?");
            $sql -> bind_param("s", $nombre);
            $sql -> execute();
            $resultado = $sql -> get_result();
            $categoria = $resultado -> fetch_assoc();
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" disabled name="nombre" type="text" 
                    value="<?php echo $categoria["nombre"] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <input class="form-control" name="descripcion" type="text"
                    value="<?php echo $categoria["descripcion"] ?>">
            </div>
            <div class="mb-3">
                <input type="hidden" name="nombre" value="<?php echo $categoria["nombre"] ?>">
                <input class="btn btn-primary" type="submit" value="Modificar">
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>