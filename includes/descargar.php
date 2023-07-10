<?php
// Establecer encabezados para descargar el archivo CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="elecciones.csv"');

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elecciones";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL para obtener los elecciones de la tabla
$sql = "SELECT * FROM votantes";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Abrir archivo en modo escritura
    $file = fopen('php://output', 'w');

    // Escribir la primera fila con los nombres de las columnas
    $columnas = array("id", "cedula", "nombre", "apellidos", "lider", "barrio", "direccion", "telefono", "puesto", "mesa", "creado_por", "fecha_registro");
    fputcsv($file, $columnas);

    // Iterar sobre los elecciones y escribirlos en el archivo CSV
    while ($row = $result->fetch_assoc()) {
        $fila = array(
            $row["id"],
            $row["cedula"],
            $row["nombre"],
            $row["apellidos"],
            $row["lider"],
            $row["barrio"],
            $row["direccion"],
            $row["telefono"],
            $row["puesto"],
            $row["mesa"],
            $row["creado_por"],
            $row["fecha_registro"]
        );
        fputcsv($file, $fila);
    }

    // Cerrar el archivo
    fclose($file);
} else {
    echo "No se encontraron elecciones.";
}

$conn->close();
exit;
?>




