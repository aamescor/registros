<?php
session_start();
// Verificar si el usuario tiene la sesión iniciada
if (!isset($_SESSION['usuario'])) {
  // Si el usuario no tiene la sesión iniciada, redireccionar a la página de inicio de sesión
  header("Location: login.php");
  exit;
}

// Verificar el rol del usuario
$rol = $_SESSION['rol'];
if ($rol !== "digitador" && $rol !== "admin") {
  // Si el rol del usuario no es "digitador" ni "administrador", redireccionar a una página de acceso no autorizado
  header("Location: Acceso_no_autorizado.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Lideres</title>
  <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<style>
  /* Estilos para la tabla */

  #lider {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  table {
    width: 100%;
    max-width: 600px;
    /* Puedes ajustar el ancho máximo según tus necesidades */
    border-collapse: collapse;
    margin: 0 auto;
    border: 3px solid gray;
    /* Agrega un borde de 1px y color #ddd */
  }

  th,
  td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  th {
    background-color: #f2f2f2;
  }

  .back-button {
    display: block;
    text-align: left;
    margin: 10px;
    font-size: 16px;
    color: #007bff;
    text-decoration: none;
  }

  .back-button {
    display: inline-block;
    padding: 10px 15px;
    background-color: #0056b3;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
  }

  .back-button:hover {
    background-color: #007bff;
  }
</style>

<body>

  <?php
  // Realiza la consulta para obtener los líderes y el recuento de elecciones
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "elecciones";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
  }

  $sql = "SELECT lider, COUNT(*) AS elecciones FROM votantes GROUP BY lider ORDER BY  elecciones";
  $result = $conn->query($sql);
  ?>
  <a href='index.php'>Volver</a>
  <div id="lider">
    <table>
      <tr>
        <th>N°</th>
        <th>Nombre de Lider</th>
        <th>Planillados</th>
      </tr>
      <?php
      $contador = 1;
      while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $contador; ?></td>
          <td><?php echo $row['lider']; ?></td>
          <td><?php echo $row['elecciones']; ?></td>
        </tr>
      <?php
        $contador++;
      } ?>
    </table>
  </div>
  <?php
  $conn->close();
  ?>

</body>

</html>