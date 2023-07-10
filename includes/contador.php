<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elecciones";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consulta para obtener la cantidad de elecciones (votos)
$sql = "SELECT COUNT(*) AS total FROM votantes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total = $row['total'];
} else {
    $total = 0;
}

$conn->close();

// Devuelve la cantidad de elecciones como respuesta
echo $total;
?>
