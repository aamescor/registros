
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje de Registro</title>
    <style>
        /* Estilos CSS para el mensaje */
        .mensaje {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="mensaje">
        <p>Registro exitoso</p>
    </div>
     <script>
        // Redireccionar a administrador.php después de 3 segundos
        setTimeout(function() {
            window.location.href = "admin_usuarios.php";
        }, 2000);
    </script>
</body>
</html>