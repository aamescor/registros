
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso no autorizado</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
         .mensaje {
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
         #boton-inicio {
            display: block;
            width: 200px;
            height: 40px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            line-height: 40px;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<a href='index.php'>Ir a página principal</a>
<br>
    <div class="mensaje">
        <h1>Acceso No Autorizado</h1>
        <p>No tienes permisos para acceder a esta página.</p>
    </div>
    
</body>
</html>