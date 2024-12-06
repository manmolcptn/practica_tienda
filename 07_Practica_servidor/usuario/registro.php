<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1 );
 
        require('../util/conexion.php');

    ?>
</head>
<body>
    <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $tmp_usuario = $_POST["usuario"];
            $tmp_password = $_POST["password"];

            $sql = "SELECT * FROM usuarios WHERE usuario = '$tmp_usuario'";
            $resultado = $_conexion -> query($sql);
            
            if ($resultado -> num_rows == 1) $err_registro = "El usuario ya existe";
            else{

                if($tmp_usuario == "") $err_usuario = "El usuario es obligatorio.";
                else{
                    if(strlen($tmp_usuario) < 3 || strlen($tmp_usuario) > 15) $err_usuario = "El nombre de usuario debe tener entre 3 y 15 caracteres.";
                    else{
                        $patron = "/^[a-zA-Z0-9]+$/";
                        if(!preg_match($patron, $tmp_usuario)) $err_usuario = "El usuario solo puede contener letras y números.";
                        else $usuario = $tmp_usuario;
                    }
                }

                if($tmp_password == "") $err_password = "La contraseña es obligatoria.";
                else{
                    if(strlen($tmp_password) < 8 || strlen($tmp_password) > 15) $err_password = "La contraseña debe tener entre 8 y 15 caracteres.";
                    else{
                        $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/m";
                        if(!preg_match($patron, $tmp_password)) $err_password = "La contraseña tiene que tener letras mayúsculas, minúsculas, algún número y puede tener caracteres especiales.";
                        else $password = $tmp_password;
                    }
                }

                if(isset($usuario) && isset($password)){
                    $password_cifrada = password_hash($password, PASSWORD_DEFAULT);
                
                    $sql = "INSERT INTO usuarios VALUE ('$usuario', '$password_cifrada')";
                    $_conexion -> query($sql);
                }
            } 
        }
    ?>
    <div class="container">
        <h1>Formulario de Registro</h1>
        <form action="" method="post" class="col-6" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input name="usuario" class="form-control" type="text">
                <?php if(isset($err_usuario)) echo "<div class='alert alert-danger mt-3'>$err_usuario</div>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input name="password" class="form-control" type="password">
                <?php if(isset($err_password)) echo "<div class='alert alert-danger mt-3'>$err_password</div>" ?>
                <?php if(isset($err_registro)) echo "<div class='container alert alert-danger mt-3'>$err_registro</div>";?>
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-primary" value="Registrarse">
            </div>
        </form>
        <h3>Si ya tienes una cuenta, inicia sesión!</h3>
        <a href="iniciar_sesion.php" class="btn btn-secondary">Iniciar sesión</a>
        <a href="../index.php" class="btn btn-info">Tienda</a>
    </div>
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous">
    </script>
</body>

</html>