<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verificar si el usuario tiene la sesión iniciada
if (!isset($_SESSION['usuario'])) {
    // Si el usuario no tiene la sesión iniciada, redireccionar a la página de inicio de sesión
    header("Location: login.php");
    exit;
}

// Verificar el rol del usuario
$rol = $_SESSION['rol'];
if ($rol !== "digitador" && $rol !== "admin") {
    // Si el rol del usuario no es "digitador" ni "administrador", redireccionar a una página de acceso no autorizado
    header("Location: Acceso_no_autorizado.php");
    exit;
} ?><?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verificar si el usuario tiene la sesión iniciada
if (!isset($_SESSION['usuario'])) {
    // Si el usuario no tiene la sesión iniciada, redireccionar a la página de inicio de sesión
    header("Location: login.php");
    exit;
}

// Verificar el rol del usuario
$rol = $_SESSION['rol'];
if ($rol !== "digitador" && $rol !== "admin") {
    // Si el rol del usuario no es "digitador" ni "administrador", redireccionar a una página de acceso no autorizado
    header("Location: Acceso_no_autorizado.php");
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
            color: #080808;
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


        #secion {
            display: flex;
            text-align: left;
            flex-direction: column;
            height: 100px;
            margin-top: 50px;
            justify-content: space-between;
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

        .btn-exportar:hover {
            background-color: #45a049;
        }

        .btn-exportar:active {
            background-color: #3e8e41;
        }

        .btn-exportar:focus {
            outline: none;
        }
        table{
            margin-top: 110px;
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
            <div id="crear-usuarios">
                <button onclick="window.location.href='admin_usuarios.php'" class="boton-crear">Gestion de Usuarios</button>
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
                <div>
                    <b><a href="cerrar_sesion.php">Cerrar sesión</a></b>
                </div>
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
    <div id="tabla_duplicados">
        <?php
        // Configuración de la conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "elecciones";

        // Establecer conexión
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            die("Error en la conexión: " . mysqli_connect_error());
        }

        // Consultar los registros con cédulas duplicadas y ordenar por cédula ascendente
        $query = "SELECT * FROM votantes WHERE cedula IN (SELECT cedula FROM votantes GROUP BY cedula HAVING COUNT(*) > 1) ORDER BY cedula ASC";
        $result = mysqli_query($conn, $query);

        // Verificar si se encontraron registros
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>
    <th>Cédula</th>
    <th>Nombre</th>
    <th>Apellidos</th>
    <th>Lider</th>
    <th>Barrio</th>
    <th>Direccion</th>
    <th>Telefono</th>
    <th>Puesto</th>
    <th>Mesa</th>
    </tr>";

            // Recorrer y mostrar los registros
            while ($row = mysqli_fetch_assoc($result)) {
                $cedula = $row['cedula'];
                $nombre = $row['nombre'];
                $apellidos = $row['apellidos'];
                $lider = $row['lider'];
                $barrio = $row['barrio'];
                $direccion = $row['direccion'];
                $telefono = $row['telefono'];
                $puesto = $row['puesto'];
                $mesa = $row['mesa'];


                echo "<tr>
        <td>$cedula</td>
        <td>$nombre</td>
        <td>$apellidos</td>
        <td>$lider</td>
        <td>$barrio</td>
        <td>$direccion</td>
        <td>$telefono</td>
        <td>$puesto</td>
        <td>$mesa</td>
        </tr>";
            }

            echo "</table>";
        } else {
            echo "No se encontraron registros con cédulas duplicadas.";
        }

        // Cerrar la conexión
        mysqli_close($conn);
        ?>
    </div>
</body>

</html>