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
 
        require('../conexion.php');
    ?>
</head>
<body>
    <div class="container">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $tmp_nombre = $_POST["nombre"];
                $tmp_descripcion = $_POST["descripcion"];

                if ($tmp_nombre == "") $err_nombre = "El nombre es obligatorio.";
                else {
                    if (strlen($tmp_nombre) > 30) $err_nombre = "El nombre no puede tener más de 30 caractéres.";
                    else $nombre = $tmp_nombre;
                }

                if($tmp_descripcion == "") $err_descripcion = "La descripción es obligatoria.";
                else{
                    if(strlen($tmp_descripcion) > 255) $err_descripcion = "Se permiten 255 caractéres como máximo.";
                    else{
                        $patron = "/^[a-zA-Z0-9ÁÉÍÓÚÑáéíóúñ ]+$/";
                        if(!preg_match($patron, $tmp_descripcion)) $err_descripcion = "Sólo se permiten letras, números y espacios en blanco.";
                        else $descripcion = $tmp_descripcion;
                    }
                }
                
                $sql = "INSERT INTO categorias (nombre, descripcion)
                    VALUES ('$nombre', '$descripcion')";
                $_conexion -> query($sql);
                
            }
        ?>
        <h1>Nueva Categoría</h1>
        <form action="" method="post" class="col-6" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input name="nombre" class="form-control" type="text">
                <?php if(isset($err_nombre)) echo "<span class='error'>$err_nombre</span>"?>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <textarea name="descripcion" class="form-control"></textarea>
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