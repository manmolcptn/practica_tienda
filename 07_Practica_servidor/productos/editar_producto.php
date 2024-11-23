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
                $id_producto = $_POST["id_producto"];
                $tmp_nombre = $_POST["nombre"];
                $tmp_precio = $_POST["precio"];
                $tmp_categoria = $_POST["categoria"];
                $tmp_stock = $_POST["stock"];
                $nombre_imagen = $_FILES["imagen"]["name"];
                $tmp_descripcion = $_POST["descripcion"];

                if ($tmp_nombre == "") $err_nombre = "El nombre es obligatorio.";
                else {
                    if (strlen($tmp_nombre) > 50) $err_nombre = "El nombre no puede tener más de 50 caractéres.";
                    else $nombre = $tmp_nombre;
                }

                if ($tmp_precio == "") $err_precio = "El precio es obligatorio.";
                else {
                    if (floatval($tmp_precio) > 9999.99 || floatval($tmp_precio) < 0) $err_precio = "El precio debe ser positivo o menor que 9999.99€";
                    else{
                        $patron = "/^[0-9.]+$/";
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
                    $patron = "/^[0-9]+$/";
                    if (!preg_match($patron, $tmp_stock)) $err_stock = "Sólo se permiten 3 valores numéricos.";
                    else $stock = $tmp_stock;
                }

                if(strlen($tmp_descripcion) > 255) $err_descripcion = "Se permiten 255 caractéres como máximo.";
                else{
                    $patron = "/^[a-zA-Z0-9ÁÉÍÓÚÑáéíóúñ ]+$/";
                    if(!preg_match($patron, $tmp_descripcion)) $err_descripcion = "Sólo se permiten letras, números y espacios en blanco.";
                    else $descripcion = $tmp_descripcion;
                }

                if(isset($nombre) && isset($precio) && isset($categoria)){
                    $direccion_temporal = $_FILES["imagen"]["tmp_name"];
                    $tmp_nombre_imagen = $_FILES["imagen"]["name"];
                    if (strlen($tmp_nombre_imagen) > 60) $err_nombre_imagen = "Nombre de la imagen demasiado largo.";
                    else $nombre_imagen = $tmp_nombre_imagen;
                    move_uploaded_file($direccion_temporal, "imagenes/$nombre_imagen");
                    $sql = "UPDATE productos SET
                            nombre = '$nombre',
                            precio = $precio,
                            categoria = '$categoria',
                            stock = $stock, 
                            imagen = '../imagenes/$nombre_imagen',
                            descripcion = '$descripcion'
                        WHERE id_producto = $id_producto";

                    $_conexion -> query($sql);
                }
                
            }

            echo "<h1>Producto " .$_GET["id_producto"] . "</h1>";

            $id_producto = $_GET["id_producto"];
            $sql = "SELECT * FROM productos WHERE id_producto = '$id_producto'";
            $resultado = $_conexion -> query($sql);
            $producto = $resultado -> fetch_assoc();
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" name="nombre" type="text" 
                    value="<?php echo $producto["nombre"] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input class="form-control" name="precio" type="text" 
                    value="<?php echo $producto["precio"] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="categoria">
                <option value="<?php echo $producto["categoria"] ?>" selected>
                        <?php echo $producto["categoria"] ?>
                    </option>
                    <?php foreach($categorias as $categoria) { ?>
                        <option value="<?php echo $categoria ?>">
                            <?php echo $categoria ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input class="form-control" name="stock" type="text"
                    value="<?php echo $producto["stock"] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <input class="form-control" name="descripcion" type="text"
                    value="<?php echo $producto["descripcion"] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input class="form-control" name="imagen" type="file">
            </div>
            <div class="mb-3">
                <input type="hidden" name="id_producto" value="<?php echo $producto["id_producto"] ?>">
                <input class="btn btn-primary" type="submit" value="Modificar">
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>