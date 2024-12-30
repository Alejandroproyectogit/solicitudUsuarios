<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SESSION['id_rol'] == 2) {
    header('Location: vistaUsuarios.php');
    exit();
}


require "../conexion/conexion.php";
$filtroSeleccionado = isset($_POST['filtro']) ? $_POST['filtro'] : 'Pendientes';
$mesActual = date("m");

$sql = "SELECT
            s.id_solicitud,
            s.tipoDocumento,
            s.documento,
            s.nombres,
            s.apellidos,
            s.telefono,
            s.correo,
            s.cargo,
            sis.nombreSistema,
            s.nombreUsuarioCopia,
            s.documentoUsuCopia,
            s.fechaSolicitud,
            u.nombre,
            s.estado
        FROM
            solicitudes s
        INNER JOIN usuarios u ON
            s.QuienSolicita = u.id
        INNER JOIN sistemas_de_informacion sis ON
            s.id_sistema = sis.id
        ";

if ($filtroSeleccionado === 'Pendientes') {
    $sql .= " WHERE s.estado = 'PENDIENTE' AND fechaSolicitud = '$mesActual' ORDER BY s.id_solicitud DESC";
} elseif ($filtroSeleccionado === 'Realizados') {
    $sql .= " WHERE s.estado = 'CREADO' ORDER BY s.id_solicitud DESC";
}

$ver = $con->prepare($sql);
$ver->execute();
$resultado = $ver->fetchAll(PDO::FETCH_ASSOC);




?>




<!DOCTYPE html>
<html lang="en">

<head>
    <?php require "../secciones/head.php"; ?>
</head>

<body>
    <div class="app menu-off-canvas align-content-stretch d-flex flex-wrap">
        <div class="app-sidebar">
            <div class="logo">
                <a href="#" class="logo-icon"><span class="logo-text">Clinaltec</span></a>
                <div class="sidebar-user-switcher user-activity-online">
                    <a href="#">

                        <span class="user-info-text">Bienvenid@ <?php echo $_SESSION['nombre']; ?> <br><span class="user-state-info">Administrador</span><span class="activity-indicator"></span></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="app-container">

            <?php require "../secciones/headerAdmin.php"; ?>
            <div class="app-content">
                <div class="content-wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <h1>Solicitudes</h1>
                                <span>Usuarios Pendientes Por Crear</span>
                                <select id="asignarFiltro" name="asignarFiltro" class="form-select" aria-label="Default select example">
                                    <option value="Pendientes" <?php echo ($filtroSeleccionado == 'Pendientes') ? 'selected' : ''; ?>>Pendientes</option>
                                    <option value="Realizados" <?php echo ($filtroSeleccionado == 'Realizados') ? 'selected' : ''; ?>>Realizados</option>
                                    <option value="Todos" <?php echo ($filtroSeleccionado == 'Todos') ? 'selected' : ''; ?>>Todos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div id="table-refresh">
                                            <table id="datatable1" class="table display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <td>Tipo de Documento</td>
                                                        <td>Documento</td>
                                                        <td>Nombre</td>
                                                        <td>Apellidos</td>
                                                        <td>Telefono</td>
                                                        <td>Correo</td>
                                                        <td>Cargo</td>
                                                        <td>Sistemas requerido</td>
                                                        <td>Usuario a Copiar</td>
                                                        <td>Documento</td>
                                                        <td>Quien Solicita</td>
                                                        <td>Estado</td>
                                                        <td>Cuando Se Solicito</td>
                                                        <td>Acción</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($resultado)): ?>
                                                        <?php foreach ($resultado as $fila): ?>
                                                            <tr>
                                                                <td><?php echo $fila["tipoDocumento"]; ?></td>
                                                                <td><?php echo $fila["documento"]; ?></td>
                                                                <td><?php echo $fila["nombres"]; ?></td>
                                                                <td><?php echo $fila["apellidos"]; ?></td>
                                                                <td><?php echo $fila["telefono"]; ?></td>
                                                                <td><?php echo $fila["correo"]; ?></td>
                                                                <td><?php echo $fila["cargo"]; ?></td>
                                                                <td><?php echo $fila["nombreSistema"]; ?></td>
                                                                <td><?php echo $fila["nombreUsuarioCopia"]; ?></td>
                                                                <td><?php echo $fila["documentoUsuCopia"]; ?></td>
                                                                <td><?php echo $fila["nombre"]; ?></td>
                                                                <td><?php echo $fila["estado"]; ?></td>
                                                                <td><?php echo $fila["fechaSolicitud"] ?></td>
                                                                <td>
                                                                    <?php if ($fila["estado"] === "PENDIENTE"): ?>
                                                                        <form class="cambioEstado">
                                                                            <input name="cambio" value="<?php echo $fila['id_solicitud']; ?>" hidden>
                                                                            <button type="submit" class="btn btn-success align-middle"><i class="bi bi-check"></i></button>
                                                                        </form>
                                                                    <?php endif; ?>

                                                                    <!--<form action="deleteSolicitudAdmin.php" method="POST">
                                                                        <button type="submit" class="btn btn-danger align-middle" id="eliminarSolicitud" name="eliminarSolicitud" value="<?php echo $fila['id_solicitud']; ?>"><i class="bi bi-trash-fill"></i></button>
                                                                    </form>-->
                                                                </td>
                                                            </tr>
                                                            
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>


                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Javascripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        document.getElementById('asignarFiltro').addEventListener('change', function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'filtro';
                input.value = this.value;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            });                                                                


        const formularios = document.querySelectorAll(".cambioEstado");

        formularios.forEach((formulario) => {
            formulario.addEventListener("submit", function(evento) {
                evento.preventDefault();

                const idEstado = new FormData(evento.target);
                const idEnviado = Object.fromEntries(idEstado.entries());

                fetch("cambioEstado.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(idEnviado),
                    })
                    .then((response) => response.json())
                    .then((resultado) => {
                        Swal.fire({
                            title: "EXITO",
                            text: resultado.message,
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    })
                    .catch((error) => console.error("error: ", error));
            });
        });
    </script>
    <script src="../assets/plugins/jquery/jquery-3.5.1.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="../assets/plugins/pace/pace.min.js"></script>
    <script src="../assets/plugins/highlight/highlight.pack.js"></script>
    <script src="../assets/plugins/datatables/datatables.min.js"></script>
    <script src="../assets/js/main.min.js"></script>
    <script src="../assets/js/custom.js"></script>
    <script src="../assets/js/pages/datatables.js"></script>
    <script>
        $('#datatable1').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        },
        order: [[0, 'desc']] 
    });
    </script>



</body>

</html>