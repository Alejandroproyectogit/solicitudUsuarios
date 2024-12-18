<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet" />
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet" />
    <!-- MDB -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.1.0/mdb.min.css"
        rel="stylesheet" />

</head>

<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="../imagenes/image.png"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="card-body p-5 shadow-5 text-center">
                        <form id="loginForm" method="POST">
                            <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                                <h2 class="fw-bold mb-4">Login</h2>

                            </div>

                            <!-- Email input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="form3Example3" class="form-control form-control-lg" name="usuario" required />
                                <label class="form-label" for="form3Example3">Usuario</label>
                            </div>

                            <!-- Password input -->
                            <div data-mdb-input-init class="form-outline mb-3">
                                <input type="password" id="form3Example4" class="form-control form-control-lg" name="pass" required>
                                <label class="form-label" for="form3Example4">Contraseña</label>
                            </div>


                            <div class="text-center text-lg-start mt-4 pt-2">
                                <button type="submit" data-mdb-ripple-init class="btn btn-primary btn-lg"
                                    style="padding-left: 2.5rem; padding-right: 2.5rem;" name="send">Ingresar</button>
                            </div>
                            <div id="hola"></div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
                Copyright © 2020. All rights reserved.
            </div>
            <!-- Copyright -->

            <!-- Right -->
            <div>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#!" class="text-white">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
            <!-- Right -->
        </div>
    </section>


    <!-- MDB -->
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.1.0/mdb.umd.min.js"></script>
    <script>
        // Escuchar el evento de envío del formulario
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar que el formulario se envíe de forma tradicional

            // Obtener los datos del formulario
            const usuario = document.getElementsByName('usuario').value;
            const pass = document.getElementByName('pass').value;

            // Validar que los campos no estén vacíos
            if (usuario === '' || pass === '') {
                alert('Por favor, complete todos los campos.');
                return;
            }

            // Crear el objeto FormData con los datos del formulario
            const formData = new FormData();
            formData.append('usuario', usuario);
            formData.append('pass', pass);

            // Realizar la petición AJAX con Fetch API
            fetch('backend/validar.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // Convertir la respuesta a JSON
                .then(data => {
                    if (data.success) {
                        // Verificar el rol y redirigir al usuario a la página correspondiente
                        if (data.id_rol === 1) {
                            window.location.href = 'html/vistaAdmin.php';
                        } else if (data.id_rol === 2) {
                            window.location.href = 'html/vistaUsuario.php';
                        } else {
                            alert('Rol no reconocido.');
                        }
                    } else {
                        alert('Usuario o contraseña incorrectos.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al procesar la solicitud.');
                });
        });
    </script>
</body>

</html>