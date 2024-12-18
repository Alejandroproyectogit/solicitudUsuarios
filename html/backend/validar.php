<?php
// Iniciar la sesión
session_start();

// Establecer la cabecera para devolver JSON
header('Content-Type: application/json; charset=utf-8');

// Conectar con la base de datos
require "../conexion/conexion.php";

// Verificar si la conexión fue exitosa
if (!$con) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Obtener la entrada JSON del cuerpo de la solicitud
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$usuario = $data["usuario"] ?? '';
$pass = $data["pass"] ?? '';

if (!empty($usuario) && !empty($pass)) {
    try {
        $stmt = $con->prepare("SELECT usuario, contrasena, id_rol FROM usuarios WHERE usuario=:usu");
        $stmt->bindParam(":usu", $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if (($result["usuario"] === $usuario) && ($result["contrasena"] === $pass)) {
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
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ocurrió un error en la base de datos',
            'error' => $e->getMessage() // Mostrar solo en desarrollo
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ocurrió un error en el servidor',
            'error' => $e->getMessage() // Mostrar solo en desarrollo
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Ingresa usuario y contraseña'
    ], JSON_UNESCAPED_UNICODE);
}
