<?php

session_start();
// Verificar si el usuario tiene la sesión iniciada
if (!isset($_SESSION['usuario'])) {
    // Si el usuario no tiene la sesión iniciada, redireccionar a la página de inicio de sesión
    header("Location: login.php");
    exit;
    // Verificar el rol del usuario
    $rol = $_SESSION['rol'];
    if ($rol !== "admin") {
        // Si el rol del usuario no es "digitador" ni "administrador", redireccionar a una página de acceso no autorizado
        header("Location: Acceso_no_autorizado.php");
        exit;
    }
} ?>

<?php
function conectar_db()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elecciones";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }
    return $conn;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $conn = conectar_db();
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, email = ?, rol = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $usuario, $email, $rol, $id);
    if ($stmt->execute() === TRUE) {
        echo "Usuario actualizado exitosamente";
        header("refresh:2; url=admin_usuarios.php");
        exit;
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }
    $conn->close();
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $conn = conectar_db();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $nombre = $row['nombre'];
            $usuario = $row['usuario'];
            $email = $row['email'];
            $rol = $row['rol'];
        } else {
            echo "No se encontró el usuario";
        }
        $conn->close();
    } else {
        echo "ID de usuario no especificado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        body {
  background-color: #f2f2f2;
  font-family: Arial, sans-serif;
}

h1 {
  text-align: center;
  margin-top: 20px;
}

form {
  max-width: 265px;
  margin: 0 auto;
  padding: 20px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 5px;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 10px;
}

input[type="text"],
input[type="email"],
select {
  width: 95%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

input[type="submit"] {
  padding: 10px 20px;
  background-color: #1b11cbcc;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: blue;
}

    </style>
   
</head>

<body>
    <a href="admin_usuarios.php">Volver</a>
    <h1>Editar Usuario</h1>
    <form action="editar-usuario.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" value="<?php echo $usuario; ?>" required>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>" required>
        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="admin" <?php echo ($rol === 'admin') ? 'selected' : ''; ?>>Administrador</option>
            <option value="digitador" <?php echo ($rol === 'digitador') ? 'selected' : ''; ?>>Digitador</option>
            <option value="consultor" <?php echo ($rol === 'consultor') ? 'selected' : ''; ?>>Consultor</option>
            <option value="candidato" <?php echo ($rol === 'candidato') ? 'selected' : ''; ?>>Candidato</option>
        </select>
        <input type="submit" value="Guardar Cambios">
    </form>
</body>

</html>