<?php
session_start();
$error_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elecciones";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Error en la conexión: " . mysqli_connect_error());
    }
    // Verificar si los campos de usuario y contraseña están vacíos
    if (empty($usuario) || empty($contrasena)) {
        $error_message = "Por favor, ingresa el nombre de usuario y la contraseña.";
    } else {
        // Utilizar sentencia preparada para evitar inyección SQL
        $query = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($contrasena, $row['contrasena'])) {
                $rol = $row['rol'];
                $_SESSION['rol'] = $rol;
                $_SESSION['usuario'] = $usuario;

                function actualizarUltimaActividad($conn, $usuarioId)
                {
                    // Obtener la fecha y hora actual
                    $fechaHoraActual = date('Y-m-d H:i:s');

                    // Actualizar la columna "ultima_actividad" en la tabla de usuarios
                    $query = "UPDATE usuarios SET ultima_actividad = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "si", $fechaHoraActual, $usuarioId);
                    mysqli_stmt_execute($stmt);
                }

                // Obtener el ID del usuario que inició sesión
                $usuarioId = $row['id'];

                // Llamar a la función para actualizar la última actividad
                actualizarUltimaActividad($conn, $usuarioId);

                // Verificar si el campo cambio_contrasena es verdadero
                if ($row['cambio_contrasena'] == 1) {
                    exit(header("Location: cambio_contrasena.php"));
                } else {
                    switch ($rol) {
                        case 'admin':
                            exit(header("Location: index.php"));
                            break;
                        case 'digitador':
                            exit(header("Location: index.php"));
                            break;
                        case 'consultor':
                            exit(header("Location: consultor.php"));
                            break;
                        case 'candidato':
                            exit(header("Location: candidato.php"));
                            break;
                        default:
                            exit(header("Location: index.php"));
                            break;
                    }
                }
            } else {
                $error_message = "Contraseña incorrecta. Por favor, inténtalo nuevamente.";
            }
        } else {
            $error_message = "Nombre de usuario incorrecto. Por favor, inténtalo nuevamente.";
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="es">
<style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        padding: 0;

    }

    h2 {
        text-align: center;
    }

    form {
        max-width: 310px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f4f4f4;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 295px;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
        width: 275px;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    input[type="submit"] {
        background-color: #0056b3;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin: 0 auto;
        /* Agregado */
        display: block;
        /* Agregado */
    }

    input[type="submit"]:hover {
        background-color: #007bff;
    }

    #box-login {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    #text-center {
        text-align: center;
    }
</style>

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <div id="box-login">
        <div id="text-center">Bienvenidos a La Plataforma De Registro De Planillas</div>
        <div id="text-center">Inicie Sesión Con Su Usuario Y Contraseña</div>
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="login.php" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required>
            <input type="submit" value="Iniciar Sesión">
        </form>
        <!--<a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>-->
    </div>
</body>

</html>