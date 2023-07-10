<?php
session_start();
// Verificar el rol del usuario
$rol = $_SESSION['rol'];
if ($rol !== "admin") {
    // Si el rol del usuario no es "administrador", redireccionar a una página de acceso no autorizado
    header("Location: acceso_no_autorizado.php");
    exit;
}
?>
<?php
// Verificar si se ha enviado el formulario de eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Validar y procesar los datos según tus necesidades
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elecciones";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Eliminar el usuario de la base de datos
    $sql = "DELETE FROM usuarios WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario eliminado exitosamente";
        header("refresh:2; url=admin_usuarios.php");
        exit;
    } else {
        echo "Error al eliminar el usuario: " . $conn->error;
    }

    $conn->close();
} else {
    // Obtener el ID del usuario a eliminar desde el parámetro de la URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo "ID de usuario no especificado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<style>
    body {
        background-color: #f2f2f2;
        font-family: Arial, sans-serif;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    h1 {
        text-align: center;
        margin-top: 20px;
    }

    p {
        text-align: center;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    input[type="submit"] {
        padding: 10px 20px;
        background-color: #f30606c9;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: red;
    }
</style>

<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="estilos.css">

</head>

<body>
    <a href="admin_usuarios.php">Volver</a>
    <h1>Eliminar Usuario</h1>
    <p>¿Estás seguro de que deseas eliminar este usuario?</p>
    <form action="eliminar-usuario.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" value="Eliminar">
    </form>
</body>

</html>