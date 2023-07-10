<?php
// Verifica si se ha enviado el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $contrasena = $_POST['password'];
    $rol = $_POST['rol'];
    // Validar y procesar los datos según tus necesidades
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elecciones";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }
    // Validar si el correo electrónico es válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: registro_usuarios.php?error=email");
        exit();
    }
    // Validar si el usuario o el correo ya existen en la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? OR email = ?");
    $stmt->bind_param("ss", $usuario, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: registro_usuarios.php?error=exist");
        exit();
    } else {
        // Insertar el nuevo usuario en la base de datos
        if (defined('PASSWORD_ARGON2I')) {
            $hashedPassword = password_hash($contrasena, PASSWORD_ARGON2I);
        } else {
            $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);
        }
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, usuario, email, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $usuario, $email, $hashedPassword, $rol);
        if ($stmt->execute()) {
            // Registro exitoso, redirigir a la página de mensaje y luego a la página principal
            header("Location: mensaje_registro.php");
            exit();
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>