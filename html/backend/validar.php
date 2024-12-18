<?php

// Configuración de errores y cabeceras JSON
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

// Conexión a la base de datos
require "../../conexion/conexion.php";

// Obtener datos de la solicitud JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);
$usuario = $data["usuario"] ?? '';
$pass = $data["pass"] ?? '';

// Validar campos obligatorios
if (empty($usuario) || empty($pass)) {
    responder(false, 'Usuario y contraseña requeridos');
}

try {
    // Consulta para buscar al usuario
    $stmt = $con->prepare("SELECT usuario, contrasena, id_rol FROM usuarios WHERE usuario = :usu");
    $stmt->bindParam(":usu", $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result["contrasena"] === $pass) {
        session_start();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['id_rol'] = $result["id_rol"];
        responder(true, 'Acceso concedido', [ 'id_rol' => $result["id_rol"] ]);
    } else {
        responder(false, 'Usuario o contraseña incorrectos');
    }
} catch (PDOException $e) {
    responder(false, 'Error en la base de datos', [ 'error' => $e->getMessage() ]);
}

/**
 * Función para enviar la respuesta JSON
 *
 * @param bool $success Indica si la operación fue exitosa
 * @param string $message Mensaje de respuesta
 * @param array $data Datos adicionales opcionales
 */
function responder($success, $message, $data = []) {
    $response = array_merge(['success' => $success, 'message' => $message], $data);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}
