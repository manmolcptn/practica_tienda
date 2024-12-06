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

    
        

    ?>
</head>
<body>
    <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario = $_POST["usuario"];
            $password = $_POST["password"];

            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = $_conexion -> query($sql);
            
            if ($resultado -> num_rows == 0) $err_inicio_sesion = "El usuario no existe";
            else{
                $info_usuario = $resultado -> fetch_assoc();
                $acceso_concedido = password_verify($password, $info_usuario["contrasenia"]);
                
                if(!$acceso_concedido) echo "<div class='container alert alert-danger col-6 mt-3'>Contraseña errónea</div>";
                else {
                    //Empiezo una sesion y lo mando al index.
                    session_start();
                    $_SESSION["usuario"] = $usuario;
                    header("location: ../index.php"); 
                    exit;
                }
            }
        }
    ?>
    <div class="container">
        <h1>Formulario de inicio de sesión</h1>
        <form action="" method="post" class="col-6" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input name="usuario" class="form-control" type="text">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input name="password" class="form-control" type="password">
                <?php if(isset($err_inicio_sesion)) echo "<div class='container alert alert-danger mt-3'>$err_inicio_sesion</div>";?>
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-primary" value="Iniciar sesión">
            </div>
        </form>
        <h3>Si aún no tienes cuenta, regístrate!</h3>
        <a href="registro.php" class="btn btn-secondary">Registrarse</a>
        <a href="../index.php" class="btn btn-info">Tienda</a>
    </div>
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous">
    </script>
</body>

</html>