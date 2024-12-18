<?php
require "conexion/conexion.php";

if (isset($_GET["accion"])) {
    if ($_GET["accion"] == "validarCredenciales") {
        require "html/validacion.php";
    }
}else{
    header("location: html/login.php");
}

?>
