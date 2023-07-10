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
}
?>

<!DOCTYPE html>
<html lang="es">
<html>

<head>
    <meta charset="UTF-8">
    <title>Planillas</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        min-height: 100vh;
        margin: 0;
    }

    #box-welcome-ir {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
    }

    #welcome-message {
        margin-bottom: 10px;
        text-align: center;
    }

    .btn-container {
        display: flex;
        justify-content: center;
    }

    .btn-container a {
        display: inline-block;
        padding: 10px 15px;
        background-color: #337ab7;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    #box-eliminar {
        align-items: center;
    }

    h1 {
        text-align: center;
    }

    p {
        text-align: center;
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        margin-top: 10px;
    }

    input[name="cancelar"] {
        background-color: #FF0000;
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


<body>
    <div id="box-welcome-ir">
        <div id="welcome-message">
            <?php
            if (isset($_SESSION['usuario'])) {
                $nombreUsuario = $_SESSION['usuario'];
                echo "<p>Bienvenido, $nombreUsuario</p>";
            }
            ?>
        </div>
        <div class="btn-container">
            <?php
            if ($rol === "admin") {
                echo "<a href='index.php'>Ir a página principal</a>";
            } elseif ($rol === "digitador") {
                echo "<a href='index.php'>Ir a página principal</a>";
            } elseif ($rol === "consultor") {
                echo "<a href='consultor.php'>Ir a página principal</a>";  
            }    
            ?>
        </div>
    </div>

    <?php
    // Obtén el ID del registro a eliminar
    $id = $_GET['id'];
    // Realiza la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elecciones";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    // Verifica si se ha enviado el formulario de confirmación
    if (isset($_POST['confirmar'])) {
        // Elimina el registro de la tabla
        $sql = "DELETE FROM votantes WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Redirige a la página principal después de eliminar el registro
            header("Location: index.php");
            exit();
        } else {
            echo "Error al eliminar el registro: " . $conn->error;
        }
    } elseif (isset($_POST['cancelar'])) {
        // Redirige a la página principal sin eliminar el registro
        header("Location: index.php");
        exit();
    }
    $conn->close();
    ?>
    <div id="box-eliminar">
        <div id="delete-form">
            <h1>Eliminar Registro</h1>
            <p>¿Estás seguro?</p>
            <form method="post" action="">
                <input type="submit" name="confirmar" value="Confirmar">
                <input type="submit" name="cancelar" value="Cancelar">
            </form>
        </div>
    </div>

    <footer id="pie" class="logo-pie">
        <p>&copy; 2023 Alvaro Martinez, Desarrollador. Todos los Derechos Reservados</p>
    </footer>

</body>

</html>