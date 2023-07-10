<?php
session_start();
// Verificar el rol del usuario
$rol = $_SESSION['rol'];
if ($rol !== "admin") {
    // Si el rol del usuario no es "administrador", redireccionar a una página de acceso no autorizado
    header("Location: Acceso_no_autorizado.php");
    exit;
}

// Obtener la lista de usuarios desde la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elecciones";
$conn = new mysqli($servername, $username, $password, $dbname);
// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$query = "SELECT id, nombre, usuario, email, rol, ultima_actividad FROM usuarios";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$usuarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <style>
        /* Estilos para el contenedor */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            flex-direction: row-reverse;
        }

        #box-welcome-ir {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        #welcome-message {
            margin-right: 10px;
        }

        #crear-usuarios {
            margin-left: auto;
        }

        .boton-crear {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .boton-crear:hover {
            background-color: #45a049;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a.editar {
            display: inline-block;
            padding: 5px 10px;
            background-color: blue;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a.eliminar {
            display: inline-block;
            padding: 5px 10px;
            background-color: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a.editar:hover {
            background-color: darkblue;
        }

        a.eliminar:hover {
            background-color: darkred;
        }

        .estado {
            font-weight: bold;
        }

        footer {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }
    </style>
</head>

<body>
<a href="index.php">Volver</a>
    <header>
        <div id="crear-usuarios">
            <button onclick="window.location.href='registro_usuarios.php'" class="boton-crear">Crear Usuarios</button>
        </div>

        <div id="box-welcome-ir">
            <div id="welcome-message">
                <?php
                if (isset($_SESSION['usuario'])) {
                    $nombreUsuario = $_SESSION['usuario'];
                    echo "<p>Bienvenido, $nombreUsuario</p>";
                }
                ?>
            </div>
            
        </div>
    </header>

    <h1>Usuarios Registrados</h1>
    <div id="main-admin-usuario">
        <table>
            <thead>
                <tr>              
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="text">
                <?php foreach ($usuarios as $usuario) { ?>
                    <tr>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['usuario']; ?></td>
                        <td><?php echo $usuario['email']; ?></td>
                        <td><?php echo $usuario['rol']; ?></td>
                        <td class="estado">
                            <?php
                            // Verificar si el usuario está en línea
                            $ultimaActividad = strtotime($usuario['ultima_actividad']);
                            $tiempoActual = time();
                            $tiempoInactividad = 300; // 300 segundos = 5 minutos

                            if ($tiempoActual - $ultimaActividad <= $tiempoInactividad) {
                                echo "En línea";
                            } else {
                                echo "Fuera de línea";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="editar-usuario.php?id=<?php echo $usuario['id']; ?>" class="editar">Editar</a>
                            <a href="eliminar-usuario.php?id=<?php echo $usuario['id']; ?>" class="eliminar">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <footer id="pie" class="logo-pie">
        <p>&copy; 2023 Alvaro Martinez, Desarrollador. Todos los Derechos Reservados</p>
    </footer>
</body>

</html>
