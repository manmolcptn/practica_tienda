<?php 
    //Servidor base de datos
    $_servidor = "localhost"; //127.0.0.1 (loopback)
    $_usuario = "estudiante"; //usuario de la base de datos
    $_contasenia = "estudiante";
    $_base_de_datos = "tienda_bd";

    //Tenemos dos opciones para crear una conexion con BBDD
    //MySqli (mas simple) ó PDO (mas completa)
    $_conexion = new Mysqli($_servidor, $_usuario, $_contasenia, $_base_de_datos)
        or die("Error de conexión");
?>