<?php
$validacion_correcta = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $usuario = trim($_POST['usuario']);
   $email = trim($_POST['email']);
    if (!empty($usuario) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
       // Realiza la validación en la base de datos para verificar si el usuario y el correo electrónico son correctos
       $servername = "localhost";
       $username = "root";
       $password = "";
       $dbname = "elecciones";
       $conn = new mysqli($servername, $username, $password, $dbname);
       if ($conn->connect_error) {
           die("Error en la conexión: " . $conn->connect_error);
       }
       $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND email = ?");
       $stmt->bind_param("ss", $usuario, $email);
       $stmt->execute();
       $result = $stmt->get_result();
       if ($result->num_rows > 0) {
           $validacion_correcta = true;
       }
       $stmt->close();
       $conn->close();

       // ...

// Si el usuario y el correo electrónico son correctos, genera una nueva contraseña temporal
if ($validacion_correcta) {
    $nueva_contrasena = generarContrasenaTemporal();

    // Aquí debes implementar la lógica para actualizar la contraseña del usuario en la base de datos con la nueva contraseña temporal
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elecciones";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Verificar si el usuario necesita cambiar la contraseña (cambio_contrasena = 1)
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['cambio_contrasena'] == 1) {
            // Actualizar la contraseña y el campo cambio_contrasena en la base de datos
            $hashedPassword = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ?, cambio_contrasena = 0 WHERE usuario = ?");
            $stmt->bind_param("ss", $hashedPassword, $usuario);
            $stmt->execute();
        }
    }

    $stmt->close();
    $conn->close();

    // Muestra la nueva contraseña temporal al usuario
    echo "Tu nueva contraseña temporal es: $nueva_contrasena";
} else {
    // Si el usuario y el correo electrónico no son correctos, muestra un mensaje de error
    echo "Usuario y/o correo electrónico incorrectos. Por favor, verifica tus datos e intenta nuevamente.";
}

   }
}

function generarContrasenaTemporal()
{
   $longitud = 8;
   $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $nueva_contrasena = '';
   $max = strlen($caracteres) - 1;
    for ($i = 0; $i < $longitud; $i++) {
       $random_index = random_int(0, $max);
       $nueva_contrasena .= $caracteres[$random_index];
   }
    return $nueva_contrasena;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />

    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"] {
            padding: 10px;
            margin-bottom: 15px;
            width: 376px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <input type="submit" value="Recuperar Contraseña">
        </form>
    </div>
</body>
</html>
