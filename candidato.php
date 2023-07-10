<?php
session_start();
// Verificar el rol del usuario
$rol = $_SESSION['rol'];
if ($rol !== "candidato") {
    // Si el rol del usuario no es "administrador", redireccionar a una página de acceso no autorizado
    header("Location: acceso_no_autorizado.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 100%;
            padding: 20px;
        }

        h2 {

            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }

        @media only screen and (max-width: 600px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th,
            td {
                min-width: 100px;
            }
        }
    </style>

</head>

<body>
    <h1>Estadísticas</h1>

    <?php

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

    // Obtener la cantidad total de registros
    $sqlTotalRegistros = "SELECT COUNT(*) AS total_registros FROM votantes";
    $resultTotalRegistros = $conn->query($sqlTotalRegistros);
    $totalRegistros = $resultTotalRegistros->fetch_assoc()['total_registros'];

    // Obtener la cantidad de líderes
    $sqlTotalLideres = "SELECT COUNT(DISTINCT lider) AS total_lideres FROM votantes";
    $resultTotalLideres = $conn->query($sqlTotalLideres);
    $totalLideres = $resultTotalLideres->fetch_assoc()['total_lideres'];

    // Obtener la tabla ordenada por el número de registros en orden descendente
    $sqlTabla = "SELECT lider, COUNT(*) AS total_registros FROM votantes GROUP BY lider ORDER BY total_registros DESC";
    $resultTabla = $conn->query($sqlTabla);
    ?>


    <p><b>Cantidad Total de Votos: <?php echo $totalRegistros; ?><b></p>
    <p><b>Cantidad de Líderes: <?php echo $totalLideres; ?><b></p>

    <table>
        <tr>
            <th>Líder</th>
            <th>Votos</th>
        </tr>
        <?php while ($row = $resultTabla->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['lider']; ?></td>
                <td><?php echo $row['total_registros']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <?php
    // Cerrar la conexión con la base de datos
    $conn->close();

    ?>

</body>

</html>