<?php
session_start();
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

    #from-edit {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 80%;
        max-width: 400px;
        margin-bottom: 20px;
    }

    .form-row {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .form-row label {
        width: 120px;
        margin-right: 10px;
    }

    .form-row input[type="text"] {
        width: 100%;
        padding: 5px;
        box-sizing: border-box;
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


    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elecciones";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }
    // Obtén el ID del registro a editar
    $id = $_GET['id'];
    // Verifica si se ha enviado el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtiene los valores enviados por el formulario
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $lider = $_POST['lider'];
        $barrio = $_POST['barrio'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $puesto = $_POST['puesto'];
        $mesa = $_POST['mesa'];
        // Obtén los demás valores del formulario
        // Actualiza el registro en la base de datos
        $stmt = $conn->prepare("UPDATE votantes SET cedula = ?, nombre = ?, apellidos = ?, lider = ?, barrio = ?, direccion = ?, telefono = ?, puesto = ?, mesa = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $cedula, $nombre, $apellidos, $lider, $barrio, $direccion, $telefono, $puesto, $mesa, $id);
        if ($stmt->execute() === TRUE) {
            // Redirige a la página de visualización de la tabla después de guardar los cambios
            header("Location: index.php");
            exit();
        } else {
            echo "Error al actualizar el registro: " . $conn->error;
        }
    } else {
        // Si no se ha enviado el formulario de edición, muestra el formulario con los datos actuales del registro
        // Obtén los datos actuales del registro de la base de datos
        $stmt = $conn->prepare("SELECT * FROM votantes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cedula = $row['cedula'];
            $nombre = $row['nombre'];
            $apellidos = $row['apellidos'];
            $lider = $row['lider'];
            $barrio = $row['barrio'];
            $direccion = $row['direccion'];
            $telefono = $row['telefono'];
            $puesto = $row['puesto'];
            $mesa = $row['mesa'];
            // Obtén los demás valores del registro
        } else {
            echo "No se encontró el registro.";
            exit();
        }
    }
    $conn->close();
    ?>
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
            }
            ?>
        </div>
    </div>
<!--Linea 185-->
    <div id="from-edit">
        <!-- Muestra el formulario de edición con los valores actuales del registro -->
        <form action="editar.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-row">
                <label for="cedula">Cédula:</label>
                <input type="text" name="cedula" value="<?php echo $cedula; ?>">
            </div>
            <div class="form-row">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $nombre; ?>">
            </div>
            <div class="form-row">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" value="<?php echo $apellidos; ?>">
            </div>
            <div class="form-row">
                <label for="lider">Líder:</label>
                <input type="text" name="lider" value="<?php echo $lider; ?>">
            </div>
            <div class="form-row">
                <label for="barrio">Barrio:</label>
                <input type="text" name="barrio" value="<?php echo $barrio; ?>">
            </div>
            <div class="form-row">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" value="<?php echo $direccion; ?>">
            </div>
            <div class="form-row">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo $telefono; ?>">
            </div>
            <div class="form-row">
                <label for="puesto">Puesto:</label>
                <input type="text" name="puesto" value="<?php echo $puesto; ?>">
            </div>
            <div class="form-row">
                <label for="mesa">Mesa:</label>
                <input type="text" name="mesa" value="<?php echo $mesa; ?>">
            </div>
            <input type="submit" value="Guardar cambios">
        </form>
    </div>
    <?php
    // Verificar si se ha enviado el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Resto del código de la página

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

        // Actualizar el registro en la base de datos

        if ($stmt->execute() === TRUE) {
            // Redirigir según el rol del usuario
            if ($rol === "admin") {
                header("Location: index.php");
                exit();
            } elseif ($rol === "digitador") {
                header("Location: digitador.php");
                exit();
            }
        } else {
            echo "Error al actualizar el registro: " . $conn->error;
        }

        $conn->close();
    }
    ?>

<footer id="pie" class="logo-pie">
  <p>&copy; 2023 Alvaro Martinez, Desarrollador. Todos los Derechos Reservados</p>
</footer>
</body>

</html>