<?php
session_start();
// Verificar si el usuario tiene la sesión iniciada
if (!isset($_SESSION['usuario'])) {
    // Si el usuario no tiene la sesión iniciada, redireccionar a la página de inicio de sesión
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    if ($nueva_contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden. Por favor, inténtalo nuevamente.";
    } else {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "elecciones";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Verificar la conexión
        if (!$conn) {
            die("Error en la conexión: " . mysqli_connect_error());
        }
        $usuario = $_SESSION['usuario'];
        $contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $query = "UPDATE usuarios SET contrasena = ?, cambio_contrasena = false WHERE usuario = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $contrasena_hash, $usuario);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $success = "Cambio de Contraseña Exitoso";
            exit(header("Location: contraseña_exitosa.php"));
        } else {
            $error = "Error al actualizar la contraseña. Por favor, inténtalo nuevamente.";
        }
        mysqli_close($conn);
    }
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

    input[type="password"] {
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
        display: block;
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
    <title>Cambio de Contraseña</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <div id="box-login">
        <h2>Cambio de Contraseña</h2>
        <?php if (!empty($error)) { ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php } elseif (!empty($success)) { ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php } ?>
        <form action="cambio_contrasena.php" method="POST">
            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" name="nueva_contrasena" required>
            <label for="confirmar_contrasena">Confirmar Contraseña:</label>
            <input type="password" name="confirmar_contrasena" required>
            <input type="submit" value="Actualizar Contraseña">
        </form>
    </div>
</body>

</html>