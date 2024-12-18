<?php

$user = "root";
$pass = "";
$name = "solicitudusuarios";

try {
    $con = new PDO("mysql:host=localhost;dbname=".$name,$user,$pass);
    echo"hola";
} catch (PDOException $e) {
    echo "error ".$e->getMessage();
}

?>