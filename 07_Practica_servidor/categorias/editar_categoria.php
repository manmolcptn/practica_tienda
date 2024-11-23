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
        
        require('../conexion.php');
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
                    $sql = "UPDATE categorias SET descripcion = '$descripcion' WHERE nomber = $nombre";
                    $_conexion -> query($sql);
                }
                
            }

            echo "<h1>Categoría " .$_GET["nombre"] . "</h1>";

            $nombre = $_GET["nombre"];
            $sql = "SELECT * FROM categorias WHERE nombre = '$nombre'";
            $resultado = $_conexion -> query($sql);
            $categoria = $resultado -> fetch_assoc();
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" readonly name="nombre" type="text" 
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