<?php
// Obtener el parámetro de búsqueda
$searchCedula = isset($_GET['search']) ? $_GET['search'] : '';

// Construir la consulta SQL con el filtro de búsqueda
$sqlSearch = "SELECT * FROM votantes";
if (!empty($searchCedula)) {
  $sqlSearch .= " WHERE cedula LIKE '%$searchCedula%'";
}
$sqlSearch .= " ORDER BY id DESC";

// Ejecutar la consulta SQL y generar el resultado en formato HTML
$resultSearch = $conn->query($sqlSearch);

// Generar el contenido HTML con los resultados
if ($resultSearch->num_rows > 0) {
  while ($row = $resultSearch->fetch_assoc()) {
    // Generar el HTML para cada resultado
    // ...
  }
} else {
  // Generar el HTML para cuando no se encuentran resultados
  // ...
}
?>
