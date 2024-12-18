<?php

require "../conexion/conexion.php";


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
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario o contraseña incorrectos'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ocurrió un error en el servidor',
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Ingresa usuario y contraseña'
    ]);
}
