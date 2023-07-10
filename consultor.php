<?php
session_start();
// Verificar el rol del usuario
$rol = $_SESSION['rol'];
if ($rol !== "consultor") {
  // Si el rol del usuario no es "Consultor", redireccionar a una página de acceso no autorizado
  header("Location: acceso_no_autorizado.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<html>

<head>
  <meta charset="UTF-8">
  <title>Claudia Llanos</title>
  <link rel="stylesheet" type="text/css" href="styles.css" />

  <style>
    #caja-header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 999;
      display: flex;
      background: white;
      justify-content: space-around;
      align-items: end;
      height: 100px;
      margin: 0%;
      border: 3px solid gray;
    }

    #restantes {
      font-family: 'fajala';
      font-size: 30px;
      text-align: center;
      display: flex;
      align-items: center;
      width: 320px;
      height: 98px;
      color: #080808;
      font-weight: bold;
      animation: pulse 2s infinite;

    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.1);
      }

      100% {
        transform: scale(1);
      }
    }


    #secion {
      display: flex;
      flex-direction: column;
      height: 100px;
      justify-content: space-between;
    }


    #crear-usuarios {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 5px;
    }

    #crear-usuarios .boton-crear {
      display: flex;
      text-align: left;
      margin-top: 50px;
      flex-direction: column;
      align-items: center;
    }

    #crear-usuarios .boton-crear:hover {
      background-color: #0056b3;
    }

    #secion {
      display: flex;
      text-align: left;
      flex-direction: column;
      margin-top: 50px;
    }

    #crear-usuarios .boton-crear {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      border-radius: 4px;
    }
    #footer {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
        }
  </style>
</head>


<body>
  <!--CABECERA-->
  <!--CABECERA-->
  <header id="caja-header">

    <div id="logo" class="logo-container">
      <a href="consultor.php">
        <img src="../Claudia_llanos/assets/img/LOGO22.png" alt="Logo">
      </a>
    </div>

    <!-- CONTADOR -->
    <div id="caja-contador">
      <span>Planillados</span>
      <div id="contador">0</div>
    </div>

    <div id="restantes">
      <?php
      // Fecha actual
      $hoy = new DateTime();

      // Fecha de las elecciones (29 de octubre de 2023)
      $eelecciones = new DateTime('2023-10-29');

      // Calcula la diferencia en días entre las fechas
      $diferencia = $eelecciones->diff($hoy);
      $diasRestantes = $diferencia->days;

      // Muestra el número de días restantes
      echo "Faltan " . $diasRestantes . " Días para Las Elecciones.";
      ?>
    </div>

    <div id="secion">
      
      <div>
        <?php
        if (isset($_SESSION['usuario']) && ($_SESSION['rol'] == 'admin' || $_SESSION['rol'] == 'digitador' || $_SESSION['rol'] == 'consultor')) {
          $usuario = $_SESSION['usuario'];
          // Mostrar el nombre del usuario en la página
          echo "Bienvenido , <b>$usuario</b>";
        } else {
          // Si el usuario no tiene la sesión iniciada o no es un Administrador, digitador o consultor, redireccionar a otra página
          header("Location: login.php");
          exit;
        }
        ?>
        <div>
          <b><a href="cerrar_sesion.php">Cerrar sesión</a></b>
        </div>
      </div>

    </div>
  </header>

  <script>
    function actualizarContador() {
      // Realizar petición AJAX al archivo contador.php
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          // Actualizar el contenido del elemento HTML del contador
          document.getElementById("contador").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "./includes/contador.php", true);
      xhttp.send();
    }

    // Actualizar el contador cada segundo (1000 milisegundos)
    setInterval(actualizarContador, 1000);
  </script>
  <br>
  <?php
  // Verificar usuario y contraseña
  if (isset($usuario) && isset($contrasena)) {
    $_SESSION['usuario'] = $nombre_usuario;
    $_SESSION['rol'] = $rol;
    // Redireccionar al administrador.php
    header("Location: consultor.php");
    exit;
  }

  header('Content-Type: text/html; charset=utf-8');
  $cedula = $nombre = $apellidos = $lider = $barrio = $direccion = $telefono = $puesto = $mesa = "";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Configurar la codificación de caracteres en la conexión
    $conn->set_charset("utf8");

    // Obtener los datos enviados desde el formulario
    $cedula = test_input($_POST['cedula']);
    $nombre = test_input($_POST['nombre']);
    $apellidos = test_input($_POST['apellidos']);
    $lider = test_input($_POST['lider']);
    $barrio = test_input($_POST['barrio']);
    $direccion = test_input($_POST['direccion']);
    $telefono = test_input($_POST['telefono']);
    $puesto = test_input($_POST['puesto']);
    $mesa = test_input($_POST['mesa']);
    $fechaRegistro = date("Y-m-d H:i:s");
    $creadoPor = $_SESSION['usuario'];

    // Validar los campos
    $errors = [];

    if (!preg_match("/^[0-9]+$/", $cedula)) {
      $errors[] = "El campo Cédula debe contener solo números.";
    }

    if (empty($nombre)) {
      $errors[] = "El campo Nombre es obligatorio.";
    }

    if (empty($apellidos)) {
      $errors[] = "El campo Apellidos es obligatorio.";
    }

    if (empty($lider)) {
      $errors[] = "El campo Líder es obligatorio.";
    }

    if (empty($barrio)) {
      $errors[] = "El campo Barrio es obligatorio.";
    }

    if (empty($direccion)) {
      $errors[] = "El campo Dirección es obligatorio.";
    }

    if (!empty($telefono) && !preg_match("/^[0-9]+$/", $telefono)) {
      $errors[] = "El campo Teléfono debe contener solo números.";
    }

    if (empty($puesto)) {
      $errors[] = "El campo Puesto es obligatorio.";
    }

    if (!preg_match("/^[0-9]+$/", $mesa)) {
      $errors[] = "El campo Mesa debe contener solo números.";
    }

    if (empty($errors)) {
      // Consulta SQL para insertar los datos en la tabla votantes
      $sql = "INSERT INTO votantes (cedula, nombre, apellidos, lider, barrio, direccion, telefono, puesto, mesa,creado_por, fecha_registro)
                        VALUES ('$cedula', '$nombre', '$apellidos', '$lider', '$barrio', '$direccion', '$telefono', '$puesto', '$mesa','$creadoPor','$fechaRegistro')";

      if ($conn->query($sql) === TRUE) {
        // Redireccionar a la página administrador.php después de insertar los datos
        header("Location: administrador.php");
        exit();
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }

    // Cerrar la conexión con la base de datos
    $conn->close();
  }

  function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

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

  // Configurar la codificación de caracteres en la conexión
  $conn->set_charset("utf8");

  // Lógica del buscador por cédula
  $searchCedula = isset($_GET['search']) ? $_GET['search'] : '';

  // Construir la consulta SQL con el filtro de búsqueda
  $sqlSearch = "SELECT * FROM votantes";
  if (!empty($searchCedula)) {
    $sqlSearch .= " WHERE cedula = '$searchCedula'";
  }
  $sqlSearch .= " ORDER BY id DESC";

  // Ejecutar la consulta SQL
  $resultSearch = $conn->query($sqlSearch);

  // Lógica del filtro
  $filterField = isset($_GET['filter']) ? $_GET['filter'] : '';
  $filterText = isset($_GET['filter_text']) ? $_GET['filter_text'] : '';

  // Construir la consulta SQL con el filtro
  $sqlFilter = "SELECT * FROM votantes";
  if (!empty($filterField) && !empty($filterText)) {
    $sqlFilter .= " WHERE $filterField LIKE '%$filterText%'";
  }
  $sqlFilter .= " ORDER BY id DESC";

  // Ejecutar la consulta SQL
  $resultFilter = $conn->query($sqlFilter);

  // Cerrar la conexión con la base de datos
  $conn->close();
  ?>
  <br>
  <div id="main">
    <?php
    // Tabla
    include "includes/tabla.php";
    ?>

    <br>


  </div>

  <footer id="footer">
        <p>&copy; 2023 Alvaro Martinez, Desarrollador. Todos los Derechos Reservados</p>
        <div id="tc">
            <a href="terminos.php">Terminos y Condiciones  -</a>
            <a href="politicas.php">  Politicas de Privacidad</a>
        </div>
    </footer>

</body>

</html>