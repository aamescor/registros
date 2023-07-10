<?php
session_start();
// Verificar si el usuario tiene la sesión iniciada
if (!isset($_SESSION['usuario'])) {
    // Si el usuario no tiene la sesión iniciada, redireccionar a la página de inicio de sesión
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Usuarios</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<style>
    #volver{
        display: flex;
        align-items: flex-start;
        width: 100%;
    }
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }

    .container {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 70%;
    }

    #box-form-registro {
        width: 100%;
        max-width: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h2 {
        text-align: center;
    }

    label {
        display: block;
        width: 100%;
        margin-bottom: 5px;
        text-align: center;
    }


    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
        width: 100%;
        padding: 5px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    .welcome-message {
        text-align: center;
        margin-bottom: 20px;
    }

    .btn-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .btn-container a {
        display: inline-block;
        padding: 10px 15px;
        background-color: #337ab7;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    footer {
        background-color: #f5f5f5;
        padding: 10px;
        text-align: center;
        width: 100%;
    }

    @media (max-width: 480px) {
        #box-form-registro {
            width: 50%;
            max-width: 400px;
            margin: 0 auto;
        }
    }
</style>

<body>

    <div id="volver"><a href="admin_usuarios.php">Volver</a></div>
    <div class="container">
        <?php
        if (isset($_SESSION['usuario'])) {
            $nombreUsuario = $_SESSION['usuario'];
            echo "<p>Bienvenido, $nombreUsuario</p>";
        }
        ?>


        <div id="welcome-message"></div>

        <div id="box-form-registro">
            <h2>Registro de Usuarios</h2>

            <form action="procesar_registro.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required pattern="^(?=.*\d)(?=.*[a-zA-Z]).{8,}$" title="La contraseña debe contener al menos 8 caracteres y combinar letras y números">

                <label for="rol">Rol:</label>
                <select id="rol" name="rol">
                    <option value="admin">Administrador</option>
                    <option value="digitador">Digitador</option>
                    <option value="consultor">Consultor</option>
                    <option value="candidato">Candidato</option>
                </select>

                <input type="submit" value="Registrar">
            </form>
        </div>
    </div>

    <footer id="pie" class="logo-pie">
        <p>&copy; 2023 Alvaro Martinez, Desarrollador. Todos los Derechos Reservados</p>
    </footer>
</body>

</html>