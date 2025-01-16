<?php
    error_reporting( E_ALL );
    ini_set( "display_errors", 1 );

    $_servidor = "localhost";
    $_usuario = "estudiante";
    $_contrasena = "estudiante";
    $_base_de_datos = "tienda_bd";

    try {
        $_conexion = new PDO("mysql:host=$_servidor;db_name=$_base_de_datos",
        $_usuario,
        $_contrasena);
        $_conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        die("Conexión fallida: ".$e -> getMessage());
    }
?>