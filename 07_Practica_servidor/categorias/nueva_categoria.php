<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Categoría</title>
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
    <style>
        .error{
            color: red;
        }
    </style>
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
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $tmp_nombre = $_POST["nombre"];
                $tmp_descripcion = $_POST["descripcion"];

                $sql = "SELECT * FROM categorias WHERE nombre = '$tmp_nombre'";
                $resultado = $_conexion -> query($sql);
            
                if ($resultado -> num_rows == 1) $err_categoria = "La categoria ya existe.";
                else{
                    
                    if (strlen($tmp_nombre) < 2 || strlen($tmp_nombre) > 30) $err_nombre = "El nombre debe ser entre 2 y 30 caracteres.";
                    else{
                        $patron = "/^[a-zA-ZÁÉÍÓÚÑáéíóúñ ]+$/";
                        if(!preg_match($patron, $tmp_nombre)) $err_nombre = "Sólo se permiten letras y espacios en blanco.";
                        else $nombre = $tmp_nombre;
                    }

                    if(strlen($tmp_descripcion) == 0) $err_descripcion = "La descripción es obligatoria.";
                    else{
                        if(strlen($tmp_descripcion) > 255) $err_descripcion = "Se permiten 255 caractéres como máximo.";
                        else{
                            $patron = "/^[a-zA-Z0-9ÁÉÍÓÚÑáéíóúñ ]+$/";
                            if(!preg_match($patron, $tmp_descripcion)) $err_descripcion = "Sólo se permiten letras, números y espacios en blanco.";
                            else $descripcion = $tmp_descripcion;
                        }
                    }
                
                    if(isset($nombre) && isset($descripcion)){
                        $sql = "INSERT INTO categorias (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
                        $_conexion -> query($sql);
                        echo "<div class='container alert alert-success mt-3'>La categoría $nombre ha sido insertada correctamente!!</div>";
                    }
                }
            }
        ?>
        <h1>Nueva Categoría</h1>
        <form action="" method="post" class="col-6" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input name="nombre" class="form-control" type="text">
                <?php if(isset($err_nombre)) echo "<div class='container alert alert-danger mt-3'>$err_nombre</div>";?>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <textarea name="descripcion" class="form-control"></textarea>
                <?php if(isset($err_descripcion)) echo "<div class='container alert alert-danger mt-3'>$err_descripcion</div>";?>
                <?php if(isset($err_categoria)) echo "<div class='container alert alert-danger mt-3'>$err_categoria</div>";?>
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-primary" value="Añadir">
                <a href="index.php" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous">
    </script>
</body>
</html>