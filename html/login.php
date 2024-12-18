<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.1.0/mdb.min.css" rel="stylesheet">
</head>
<body>
    <main class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="../imagenes/image.png" class="img-fluid" alt="Imagen de muestra">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form id="loginForm">
                        <h2 class="fw-bold mb-4">Login</h2>
                        <div class="form-outline mb-4">
                            <input type="text" id="usuario" class="form-control form-control-lg" name="usuario" required>
                            <label class="form-label" for="usuario">Usuario</label>
                        </div>
                        <div class="form-outline mb-3">
                            <input type="password" id="pass" class="form-control form-control-lg" name="pass" required>
                            <label class="form-label" for="pass">Contraseña</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Ingresar</button>
                        <div id="hola"></div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.1.0/mdb.umd.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const usuario = document.getElementById('usuario').value.trim();
            const pass = document.getElementById('pass').value.trim();

            if (!usuario || !pass) {
                alert('Por favor, complete todos los campos.');
                return;
            }

            const formData = new FormData();
            formData.append('usuario', usuario);
            formData.append('pass', pass);

            fetch('backend/validar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.id_rol === 1 ? 'html/vistaAdmin.php' : 'html/vistaUsuario.php';
                } else {
                    alert(data.message || 'Usuario o contraseña incorrectos.');
                }
            })
            .catch(error => alert(`Error: ${error.message}`));
        });
    </script>
</body>
</html>
