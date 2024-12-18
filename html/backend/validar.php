<?php
// Habilitar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Establecer la cabecera JSON
header('Content-Type: application/json; charset=utf-8');

// Conectar con la base de datos
require "../../conexion/conexion.php";



$usuario = $data["usuario"] ?? '';
$pass = $data["pass"] ?? '';

if (!empty($usuario) && !empty($pass)) {
    try {
        $stmt = $con->prepare("SELECT usuario, contrasena, id_rol FROM usuarios WHERE usuario=:usu");
        $stmt->bindParam(":usu", $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result["contrasena"] === $pass) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['id_rol'] = $result["id_rol"];
            echo json_encode([
                'success' => true,
                'message' => 'Acceso concedido',
                'id_rol' => $result["id_rol"]
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario o contraseña incorrectos'
            ], JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en la base de datos',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario y contraseña requeridos'
    ], JSON_UNESCAPED_UNICODE);
}
