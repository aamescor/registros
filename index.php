<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verificar usuario y contraseña
if (isset($usuario) && isset($contrasena)) {
    $_SESSION['usuario'] = $nombre_usuario;
    $_SESSION['rol'] = $rol;
    // Redireccionar al index.php
    header("Location: index.php");
    exit;
}
// Verificar si el usuario tiene la sesión iniciada
if (isset($_SESSION['usuario'])) {
    // Verificar el rol del usuario
    $rol = $_SESSION['rol'];
    if ($rol !== "digitador" && $rol !== "admin") {
        // Si el rol del usuario no es "digitador" ni "administrador", redireccionar a una página de acceso no autorizado
        header("Location: Acceso_no_autorizado.php");
        exit;
    }
} else {
    // Si el usuario no tiene la sesión iniciada, redireccionar a la página de inicio de sesión
    header("Location: login.php");
    exit;
} ?>
<!DOCTYPE html>
<html lang="es">
<html>

<head>
    <meta charset="UTF-8">
    <title>Claudia Llanos</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <style>
        #restantes {
            font-family: 'fajala';
            font-size: 30px;
            text-align: center;
            display: flex;
            align-items: center;
            width: 320px;
            height: 98px;
            color: white;
            font-weight: bold;
            animation: pulse 2s infinite;

        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        #caja-contador {
            display: flex;
            margin-right: 20px;
            width: 180px;
            align-self: center;
            justify-content: space-around;
            flex-direction: column;
            align-items: flex-start;

        }

        #caja-contador span {
            font-family: "fajala", Helvetica;
            font-weight: bold;
            font-size: 30px;
            color: white;
            margin-right: 5px;
        }

        #contador {

            padding: 5px 10px;
            border-radius: 200px;
            font-size: 30px;
            color: white;
            font-weight: bold;
            font-family: "fajala", Helvetica;
        }


        #secion {
            display: flex;
            text-align: end;
            flex-direction: column;
            width: auto;
            height: 100px;
            margin-top: 50px;
            justify-content: space-around;
            color: white;
            align-items: flex-end;
        }


        #crear-usuarios {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 5px;
        }

        #crear-usuarios .boton-crear {
            display: flex;
            text-align: left;
            margin-top: 50px;
            flex-direction: column;
            align-items: center;
        }

        #crear-usuarios .boton-crear:hover {
            background-color: #0056b3;
        }

        #crear-usuarios .boton-crear {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        #footer {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
        }

        #tc {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .btn-exportar {
            display: inline-block;
            padding: 10px 20px;
            height: 15px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>


<body>
    <!--CABECERA-->
    <header id="caja-header">

        <div id="logo" class="logo-container">
            <a href="index.php">
                <img src="../Claudia_llanos/assets/img/LOGO22.png" alt="Logo">
            </a>
        </div>

        <!-- CONTADOR -->
        <div id="caja-contador">
            <span>Planillados</span>
            <div id="contador">0</div>
        </div>

        <div id="restantes">
            <?php
            // Fecha actual
            $hoy = new DateTime();

            // Fecha de las elecciones (29 de octubre de 2023)
            $eelecciones = new DateTime('2023-10-29');

            // Calcula la diferencia en días entre las fechas
            $diferencia = $eelecciones->diff($hoy);
            $diasRestantes = $diferencia->days;

            // Muestra el número de días restantes
            echo "Faltan " . $diasRestantes . " Días para Las Elecciones.";
            ?>
        </div>

        <div id="secion">
        <div><!--Fecha-->
                <?php
                // Establecer la zona horaria
                date_default_timezone_set('America/Bogota');

                // Obtener la fecha y hora actual
                $fechaActual = date('d/m/Y');
                $horaActual = date('h:i A');

                // Mostrar la fecha y hora en el encabezado
                echo "<div id='fecha-hora'>Fecha: $fechaActual | Hora: $horaActual</div>";
                ?>
            </div>
            <div id="crear-usuarios">
                <b><a href="admin_usuarios.php">Gestion de Usuarios</a></b>
            </div>
            <div>
                <?php
                // Verificar si el usuario tiene la sesión iniciada y es un administrador o digitador
                if (isset($_SESSION['usuario']) && ($_SESSION['rol'] == 'admin' || $_SESSION['rol'] == 'digitador')) {
                    $usuario = $_SESSION['usuario'];
                    // Mostrar el nombre del usuario en la página
                    echo "Bienvenido, $usuario";
                } else {
                    // Si el usuario no tiene la sesión iniciada o no es un Administrador o digitador, redireccionar a otra página
                    header("Location: login.php");
                    exit;
                }
                ?>
            </div>
          
            <div>
                <b><a href="cerrar_sesion.php">Cerrar sesión</a></b>
            </div>


        </div>
    </header>

    <script>
        function actualizarContador() {
            // Realizar petición AJAX al archivo contador.php
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    // Actualizar el contenido del elemento HTML del contador
                    document.getElementById("contador").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "./includes/contador.php", true);
            xhttp.send();
        }

        // Actualizar el contador cada segundo (1000 milisegundos)
        setInterval(actualizarContador, 1000);
    </script>

    <?php


    header('Content-Type: text/html; charset=utf-8');
    $cedula = $nombre = $apellidos = $lider = $barrio = $direccion = $telefono = $puesto = $mesa = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Establecer la conexión con la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "elecciones";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error en la conexión: " . $conn->connect_error);
        }

        // Configurar la codificación de caracteres en la conexión
        $conn->set_charset("utf8");

        // Obtener los datos enviados desde el formulario
        $cedula = test_input($_POST['cedula']);
        $nombre = test_input($_POST['nombre']);
        $apellidos = test_input($_POST['apellidos']);
        $lider = test_input($_POST['lider']);
        $barrio = test_input($_POST['barrio']);
        $direccion = test_input($_POST['direccion']);
        $telefono = test_input($_POST['telefono']);
        $puesto = test_input($_POST['puesto']);
        $mesa = test_input($_POST['mesa']);
        $fechaRegistro = date("Y-m-d H:i:s");
        $creadoPor = $_SESSION['usuario'];

        // Validar los campos
        $errors = [];

        if (!preg_match("/^[0-9]+$/", $cedula)) {
            $errors[] = "El campo Cédula debe contener solo números.";
        }

        if (empty($nombre)) {
            $errors[] = "El campo Nombre es obligatorio.";
        }

        if (empty($apellidos)) {
            $errors[] = "El campo Apellidos es obligatorio.";
        }

        if (empty($lider)) {
            $errors[] = "El campo Líder es obligatorio.";
        }

        if (empty($barrio)) {
            $errors[] = "El campo Barrio es obligatorio.";
        }

        if (empty($direccion)) {
            $errors[] = "El campo Dirección es obligatorio.";
        }

        if (!empty($telefono) && !preg_match("/^[0-9]+$/", $telefono)) {
            $errors[] = "El campo Teléfono debe contener solo números.";
        }

        if (empty($puesto)) {
            $errors[] = "El campo Puesto es obligatorio.";
        }

        if (!preg_match("/^[0-9]+$/", $mesa)) {
            $errors[] = "El campo Mesa debe contener solo números.";
        }

        if (empty($errors)) {
            // Consulta SQL para insertar los datos en la tabla votantes
            $sql = "INSERT INTO votantes (cedula, nombre, apellidos, lider, barrio, direccion, telefono, puesto, mesa,creado_por, fecha_registro)
                        VALUES ('$cedula', '$nombre', '$apellidos', '$lider', '$barrio', '$direccion', '$telefono', '$puesto', '$mesa','$creadoPor','$fechaRegistro')";

            if ($conn->query($sql) === TRUE) {
                // Redireccionar a la página index.php después de insertar los datos
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Cerrar la conexión con la base de datos
        $conn->close();
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Establecer la conexión con la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elecciones";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Configurar la codificación de caracteres en la conexión
    $conn->set_charset("utf8");

    // Lógica del buscador por cédula
    $searchCedula = isset($_GET['search']) ? $_GET['search'] : '';

    // Construir la consulta SQL con el filtro de búsqueda
    $sqlSearch = "SELECT * FROM votantes";
    if (!empty($searchCedula)) {
        $sqlSearch .= " WHERE cedula = '$searchCedula'";
    }
    $sqlSearch .= " ORDER BY id DESC";

    // Ejecutar la consulta SQL
    $resultSearch = $conn->query($sqlSearch);

    // Lógica del filtro
    $filterField = isset($_GET['filter']) ? $_GET['filter'] : '';
    $filterText = isset($_GET['filter_text']) ? $_GET['filter_text'] : '';

    // Construir la consulta SQL con el filtro
    $sqlFilter = "SELECT * FROM votantes";
    if (!empty($filterField) && !empty($filterText)) {
        $sqlFilter .= " WHERE $filterField LIKE '%$filterText%'";
    }
    $sqlFilter .= " ORDER BY id DESC";

    // Ejecutar la consulta SQL
    $resultFilter = $conn->query($sqlFilter);

    // Cerrar la conexión con la base de datos
    $conn->close();
    ?>
    <div id="main">
        <div class="form-container">
            <div class="ingresar-datos">
                <h2>Ingresar Datos</h2>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="form-row">
                    <label for="cedula">Cédula:</label>
                    <input type="text" name="cedula" id="cedula" pattern="[0-9]+" required value="<?php echo $cedula; ?>">
                </div>
                <div class="form-row">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required value="<?php echo $nombre; ?>">
                </div>
                <div class="form-row">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" name="apellidos" id="apellidos" required value="<?php echo $apellidos; ?>">
                </div>
                <div class="form-row">
                    <label for="lider">Líder:</label>
                    <input type="text" name="lider" id="lider" required value="<?php echo $lider; ?>">
                </div>
                <div class="form-row">
                    <label for="barrio">Barrio:</label>
                    <input type="text" name="barrio" id="barrio" required value="<?php echo $barrio; ?>">
                </div>
                <div class="form-row">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" id="direccion" required value="<?php echo $direccion; ?>">
                </div>
                <div class="form-row">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" pattern="[0-9]+" value="<?php echo $telefono; ?>">
                </div>
                <div class="form-row">
                    <label for="puesto">Puesto:</label>
                    <input type="text" name="puesto" id="puesto" required value="<?php echo $puesto; ?>">
                </div>
                <div class="form-row">
                    <label for="mesa">Mesa:</label>
                    <input type="text" name="mesa" id="mesa" pattern="[0-9]+" required value="<?php echo $mesa; ?>">
                </div>
                <div class="form-row">
                    <input type="submit" value="Agregar">
                </div>

            </form>

        </div>

    </div>

    <div class="table-container">

        <?php
        // Establecer la conexión con la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "elecciones";
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error en la conexión: " . $conn->connect_error);
        }

        // Obtener el número total de Votantes
        $totaleleccionesSql = "SELECT COUNT(*) AS total FROM votantes";
        $totaleleccionesResult = $conn->query($totaleleccionesSql);
        $totalelecciones = $totaleleccionesResult->fetch_assoc()['total'];

        // Configurar la paginación
        $eleccionesPorPagina = 10000;
        $totalPaginas = ceil($totalelecciones / $eleccionesPorPagina);

        // Obtener el número de página actual
        $paginaActual = isset($_GET['page']) ? $_GET['page'] : 1;

        // Calcular el desplazamiento
        $offset = ($paginaActual - 1) * $eleccionesPorPagina;

        // Rango de páginas a mostrar
        $rangoPaginas = 20; // Número de páginas a mostrar en cada rango
        $rangoInicio = max($paginaActual - floor($rangoPaginas / 2), 1);
        $rangoFin = min($rangoInicio + $rangoPaginas - 1, $totalPaginas);


        // Obtener el valor del filtro y el texto de filtrado
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $filterText = isset($_GET['filter_text']) ? $_GET['filter_text'] : '';

        // Construir la consulta SQL con el filtro de búsqueda
        $sqlSearch = "SELECT * FROM votantes";
        if (!empty($searchCedula)) {
            $sqlSearch .= " WHERE cedula = '$searchCedula'";
        } elseif (!empty($filter) && !empty($filterText)) {
            $sqlSearch .= " WHERE $filter LIKE '%$filterText%'";
        }
        $sqlSearch .= " ORDER BY id DESC LIMIT $eleccionesPorPagina OFFSET $offset";

        // Ejecutar la consulta SQL
        $resultSearch = $conn->query($sqlSearch);
        ?>

        <div id="filtros">
            <!-- Buscador -->
            <script>
                function filtrarTabla() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("search");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("tablaVotantes");
                    tr = table.getElementsByTagName("tr");

                    // Iterar sobre las filas de la tabla y mostrar/ocultar según el filtro
                    for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[1]; // Índice 1 corresponde a la columna de Cédula
                        if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                }
            </script>


            <div class="search-container">
                <form action="index.php" method="GET">
                    <!--<input type="text" name="search" placeholder="Buscar por Cédula" value="<?php echo $searchCedula; ?>"-->
                    <input type="text" name="search" id="search" placeholder="Buscar por Cédula" value="<?php echo $searchCedula; ?>" onkeyup="filtrarTabla()">
                    <!--<input type="submit" value="Buscar">-->
                </form>
            </div>


            <!-- Filtro -->
            <div class="filter-container">
                <form action="index.php" method="GET">
                    <select name="filter">
                        <option value="lider">Líder</option>
                        <option value="barrio">Barrio</option>
                        <option value="puesto">Puesto</option>
                        <option value="mesa">Mesa</option>
                        <option value="creado_por">Usuario</option>
                    </select>
                    <input type="text" name="filter_text" placeholder="Filtrar por texto" value="<?php echo $filterText; ?>">
                    <input type="submit" value="Filtrar">
                </form>
            </div>
            <?php
            if ($rol === "admin" || $rol === "digitador") {
                echo '<a href="./lider.php">Mostrar Líderes</a>';
            }
            ?>
            <?php
            if ($rol === "admin" || $rol === "digitador") {
                echo '<a href="./duplicados.php">Mostrar Duplicados</a>';
            }
            ?>

        </div>

        <div id="tablaVotantes" class="table-container">
            <table>
                <tr>
                    <th>#</th>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Líder</th>
                    <th>Barrio</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Puesto</th>
                    <th>Mesa</th>
                    <th>Usuario</th>
                </tr>

                <?php
                if ($resultSearch->num_rows > 0) {
                    // Inicializar el contador
                    $contador = ($paginaActual - 1) * $eleccionesPorPagina + 1;

                    // Mostrar los datos en filas de la tabla
                    while ($row = $resultSearch->fetch_assoc()) {
                        echo "<tr>
                        <td>" . $contador . "</td>
                        <td>" . $row['cedula'] . "</td>
                        <td>" . $row['nombre'] . "</td>
                        <td>" . $row['apellidos'] . "</td>
                        <td>" . $row['lider'] . "</td>
                        <td>" . $row['barrio'] . "</td>
                        <td>" . $row['direccion'] . "</td>
                        <td>" . $row['telefono'] . "</td>
                        <td>" . $row['puesto'] . "</td>
                        <td>" . $row['mesa'] . "</td>
                        <td>" . $row['creado_por'] . "</td>
                        <td><a href='editar.php?id=" . $row['id'] . "' class='btn-editar'>Editar</a></td>
                        <td><a href='eliminar.php?id=" . $row['id'] . "' class='btn btn-danger'>Eliminar</a></td>   
                    </tr>";

                        // Incrementar el contador
                        $contador++;
                    }
                } else {
                    echo "<tr><td colspan='10'>No se encontraron resultados.</td></tr>";
                }

                $conn->close();
                ?>
            </table>



        </div>
        <!-- Navegación de páginas 
    Copiar el codigo de navegacion-->

        <br>
        <br>
    </div>
    <br>
    <footer id="footer">
        <a id="csv" href="includes/descargar.php" class="btn-exportar">Exportar CSV</a>
        <p>&copy; 2023 Alvaro Martinez, Desarrollador. Todos los Derechos Reservados</p>
        <div id="tc">
            <a href="terminos.php">Terminos y Condiciones</a>
            <a href="politicas.php">Politicas de Privacidad</a>
        </div>
    </footer>

</body>

</html>