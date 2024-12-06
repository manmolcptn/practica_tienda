<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto</title>
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
            $sql = "SELECT * FROM categorias ORDER BY nombre";
            $resultado = $_conexion -> query($sql);
            $categorias = [];

            //Introduce las categorias en un nuevo array
            while($fila = $resultado -> fetch_assoc()){
                array_push($categorias, $fila["nombre"]);

            }

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $tmp_nombre = $_POST["nombre"];
                $tmp_precio = $_POST["precio"];
                $tmp_categoria = $_POST["categoria"];
                $tmp_stock = $_POST["stock"];
                $nombre_imagen = $_FILES["imagen"]["name"];
                $tmp_descripcion = $_POST["descripcion"];

                if ($tmp_nombre == "") $err_nombre = "El nombre es obligatorio.";
                else {
                    if (strlen($tmp_nombre) < 2 || strlen($tmp_nombre) > 50) $err_nombre = "El nombre debe contener entre 2 y 50 caracteres.";
                    else{
                        $patron = "/^[a-zA-Z0-9ñÑ ]+$/";
                        if(!preg_match($patron, $tmp_nombre)) $err_nombre = "El nombre solo puede contener letras, numeros y espacios en blanco.";
                        else $nombre = $tmp_nombre;
                    }
                }

                if ($tmp_precio == "") $err_precio = "El precio es obligatorio.";
                else {
                    if (floatval($tmp_precio) > 9999.99 || floatval($tmp_precio) < 0) $err_precio = "El precio debe ser positivo o menor que 9999.99€";
                    else{
                        $patron = "/^[0-9]{1,4}(\.[0-9]{1,2})?/";
                        if (!preg_match($patron, $tmp_precio)) $err_precio = "Solo puedes introducir números enteros o decimales con el punto.";
                        else $precio = $tmp_precio;
                    }
                }

                if ($tmp_categoria == "") $err_categoria = "La categoría del producto es obligatoria.";
                else{
                    if (!in_array($tmp_categoria, $categorias)) $err_categoria = "Elige una categoría válida";
                    else $categoria = $tmp_categoria;
                }

                if ($tmp_stock == "") $stock = "0";
                else {
                    if (strlen($tmp_stock) > 999 || intval($tmp_stock) < 0) $err_stock = "El stock debe ser positivo o menor de 999.";
                    else{
                        $patron = "/^[0-9]+$/";
                        if (!preg_match($patron, $tmp_stock)) $err_stock = "Sólo se permiten 3 valores numéricos.";
                        else $stock = $tmp_stock;
                    }
                }

                if(strlen($tmp_descripcion) == 0) $descripcion = "No hay descripción para este producto.";
                else{
                    if(strlen($tmp_descripcion) > 255) $err_descripcion = "Se permiten 255 caractéres como máximo.";
                    else{
                        $patron = "/^[a-zA-Z0-9ÁÉÍÓÚÑáéíóúñ ]+$/";
                        if(!preg_match($patron, $tmp_descripcion)) $err_descripcion = "Sólo se permiten letras, números y espacios en blanco.";
                        else $descripcion = $tmp_descripcion;
                    }
                }

                $direccion_temporal = $_FILES["imagen"]["tmp_name"];
                $tmp_nombre_imagen = $_FILES["imagen"]["name"];
                if (strlen($tmp_nombre_imagen) > 60) $err_nombre_imagen = "Nombre de la imagen demasiado largo.";
                else{
                    $nombre_imagen = $tmp_nombre_imagen;
                    move_uploaded_file($direccion_temporal, "../imagenes/$nombre_imagen");
                } 
                
                if(isset($nombre) && isset($precio) && isset($categoria) && !isset($err_stock) && !isset($err_descripcion) && !isset($err_imagen)){
                    $sql = "INSERT INTO productos (nombre, precio, categoria, stock, imagen, descripcion)
                        VALUES 
                        ('$nombre', '$precio', '$categoria', '$stock', '../imagenes/$nombre_imagen', '$descripcion')";
                    $_conexion -> query($sql);
                    echo "<div class='container alert alert-success mt-3'>El producto $nombre ha sido insertado correctamente!!</div>";
                }
            }
        ?>
        <h1>Nuevo Producto</h1>
        <form action="" method="post" class="col-6" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input name="nombre" class="form-control" type="text">
                <?php if(isset($err_nombre)) echo "<div class='container alert alert-danger mt-3'>$err_nombre</div>"?>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input name="precio" class="form-control" type="text">
                <?php if(isset($err_precio)) echo "<div class='container alert alert-danger mt-3'>$err_precio</div>"?>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="categoria" id="">
                    <?php foreach($categorias as $categoria) { ?>
                        <option value="<?php echo $categoria ?>">
                            <?php echo $categoria ?>
                        </option>

                    <?php  } ?>
                    <?php if(isset($err_categoria)) echo "<div class='container alert alert-danger mt-3'>$err_categoria</div>"?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input name="stock" class="form-control" type="text">
                <?php if(isset($err_stock)) echo "<div class='container alert alert-danger mt-3'>$err_stock</div>"?>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input name="imagen" class="form-control" type="file">
                <?php if(isset($err_imagen)) echo "<div class='container alert alert-danger mt-3'>$err_imagen</div>"?>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <textarea name="descripcion" class="form-control"></textarea>
                <?php if(isset($err_descripcion)) echo "<div class='container alert alert-danger mt-3'>$err_descripcion</div>"?>
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