<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1 );

        require('./util/conexion.php');
        session_start();
    ?>
</head>
<body>
<div class="container">
        <?php 
            if(isset($_SESSION["usuario"])) {
                $usuario = $_SESSION["usuario"];
                echo "<h4 class='alert alert-success mt-3'> Bienvenid@ $usuario!</h4>";
            }
        ?>
        <h1>Listado de productos</h1>
        
        <?php 
            
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $id_producto = $_POST["id_producto"];
                //echo "<h1>$id_producto</h1>";
                $sql = "DELETE FROM productos WHERE id_producto = '$id_producto'";
                $_conexion -> query($sql);
            }

            $sql = "SELECT * FROM productos";
            $resultado = $_conexion -> query($sql);
        ?>
        <?php 
            if (isset($_SESSION["usuario"])){
                echo " <a class='btn btn-danger mb-3 float-end' href='./usuario/cerrar_sesion.php'>Cerrar sesión</a>";
                echo " <a class='btn btn-dark mb-3 me-1 float-end' href='./usuario/cambiar_credenciales.php?usuario=$usuario'>Modificar contraseña</a>";
                echo " <a class='btn btn-secondary mb-3' href='./productos/index.php'>Productos</a>";
                echo " <a class='btn btn-secondary mb-3' href='./categorias/index.php'>Categorías</a>";
            } 
            else echo "<a class='btn btn-secondary mb-3' href='./usuario/iniciar_sesion.php'>Iniciar Sesión</a>";
        ?>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    //Trata el objeto resultado como un array asociativo
                    while($fila = $resultado -> fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $fila["nombre"] . "</td>";
                        echo "<td>" . $fila["precio"] . "</td>";
                        echo "<td>" . $fila["categoria"] . "</td>";
                        echo "<td>" . $fila["stock"] . "</td>";
                        echo "<td style='min-width:150px'>" . $fila["descripcion"] . "</td>";
                        echo "<td><img src=./imagen/" . $fila["imagen"]." width=150px></td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous">
    </script>
</body>
</html>