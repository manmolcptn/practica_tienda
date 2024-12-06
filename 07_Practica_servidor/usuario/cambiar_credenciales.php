<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1 );
 
        require('../util/conexion.php');

        session_start();
        if(!isset($_SESSION["usuario"])) {
            header("location: ../usuario/iniciar_sesion.php");
            exit;
        }
    ?>
</head>
<body>
    <?php
        $sql = "SELECT * FROM categorias ORDER BY nombre";
        $resultado = $_conexion -> query($sql);
        $categorias = [];

        //Introduce las categorias en un nuevo array
        while($fila = $resultado -> fetch_assoc()){
            array_push($categorias, $fila["nombre"]);
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuario = $_SESSION["usuario"];
            $tmp_password = $_POST["password"];
            $tmp_nueva_password = $_POST["confirmar_password"];

            if ($tmp_password !== $tmp_nueva_password) $err_inicio_sesion = "Las contraseñas no coinciden.";
            else{

                if($tmp_password == "") $err_password = "La contraseña es obligatoria.";
                else{
                    if(strlen($tmp_password) < 8 || strlen($tmp_password) > 15) $err_password = "La contraseña debe tener entre 8 y 15 caracteres.";
                    else{
                        $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/m";
                        if(!preg_match($patron, $tmp_password)) $err_password = "La contraseña tiene que tener letras mayúsculas, minúsculas, algún número y puede tener caracteres especiales.";
                        else $password = $tmp_password;
                    }
                }

                if($tmp_nueva_password == "") $err_password = "La contraseña es obligatoria.";
                else{
                    if(strlen($tmp_nueva_password) < 8 || strlen($tmp_nueva_password) > 15) $err_password = "La contraseña debe tener entre 8 y 15 caracteres.";
                    else{
                        $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/m";
                        if(!preg_match($patron, $tmp_nueva_password)) $err_password = "La contraseña tiene que tener letras mayúsculas, minúsculas, algún número y puede tener caracteres especiales.";
                        else $nueva_password = $tmp_nueva_password;
                    }
                }

                if(isset($password) && isset($nueva_password)){
                    $password_cifrada = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "UPDATE usuarios SET contrasenia = '$password_cifrada' WHERE usuario = '$usuario'";
                    $_conexion -> query($sql);
                    echo "<div class='container alert alert-success mt-3'>La contraseña ha sido moficada correctamente!!</div>";

                }
            }
        }
        ?>
    <div class="container">
        <h1>Cambiar credenciales</h1>
        <form action="" method="post" class="col-6" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nueva contraseña</label>
                <input name="password" class="form-control" type="password">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar nueva contraseña</label>
                <input name="confirmar_password" class="form-control" type="password">
                <?php if(isset($err_inicio_sesion)) echo "<div class='container alert alert-danger mt-3'>$err_inicio_sesion</div>";?>
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-primary" value="Cambiar contraseña">
                <a href="../index.php" class="btn btn-secondary">Volver</a>
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