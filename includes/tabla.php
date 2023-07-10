<div class="table-container">

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

    // Obtener el número total de Votantes
    $totaleleccionesSql = "SELECT COUNT(*) AS total FROM votantes";
    $totaleleccionesResult = $conn->query($totaleleccionesSql);
    $totalelecciones = $totaleleccionesResult->fetch_assoc()['total'];

    // Configurar la paginación
    $eleccionesPorPagina = 10000;
    $totalPaginas = ceil($totalelecciones / $eleccionesPorPagina);

    // Obtener el número de página actual
    $paginaActual = isset($_GET['page']) ? $_GET['page'] : 1;

    // Calcular el desplazamiento
    $offset = ($paginaActual - 1) * $eleccionesPorPagina;

    // Rango de páginas a mostrar
    $rangoPaginas = 20; // Número de páginas a mostrar en cada rango
    $rangoInicio = max($paginaActual - floor($rangoPaginas / 2), 1);
    $rangoFin = min($rangoInicio + $rangoPaginas - 1, $totalPaginas);


    // Obtener el valor del filtro y el texto de filtrado
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    $filterText = isset($_GET['filter_text']) ? $_GET['filter_text'] : '';

    // Construir la consulta SQL con el filtro de búsqueda
    $sqlSearch = "SELECT * FROM votantes";
    if (!empty($searchCedula)) {
        $sqlSearch .= " WHERE cedula = '$searchCedula'";
    } elseif (!empty($filter) && !empty($filterText)) {
        $sqlSearch .= " WHERE $filter LIKE '%$filterText%'";
    }
    $sqlSearch .= " ORDER BY id DESC LIMIT $eleccionesPorPagina OFFSET $offset";

    // Ejecutar la consulta SQL
    $resultSearch = $conn->query($sqlSearch);
    ?>

    <div id="filtros">
        <!-- Buscador -->
        <script>
            function filtrarTabla() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("search");
                filter = input.value.toUpperCase();
                table = document.getElementById("tablaVotantes");
                tr = table.getElementsByTagName("tr");

                // Iterar sobre las filas de la tabla y mostrar/ocultar según el filtro
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1]; // Índice 1 corresponde a la columna de Cédula
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>


        <div class="search-container">
            <form action="administrador.php" method="GET">
                <!--<input type="text" name="search" placeholder="Buscar por Cédula" value="<?php echo $searchCedula; ?>"-->
                <input type="text" name="search" id="search" placeholder="Buscar por Cédula" value="<?php echo $searchCedula; ?>" onkeyup="filtrarTabla()">
                <!--<input type="submit" value="Buscar">-->
            </form>
        </div>


        <!-- Filtro -->
        <div class="filter-container">
            <form action="administrador.php" method="GET">
                <select name="filter">
                    <option value="lider">Líder</option>
                    <option value="barrio">Barrio</option>
                    <option value="puesto">Puesto</option>
                    <option value="puesto">Barrio</option>
                </select>
                <input type="text" name="filter_text" placeholder="Filtrar por texto" value="<?php echo $filterText; ?>">
                <input type="submit" value="Filtrar">
            </form>
        </div>
        <?php
        if ($rol === "admin" || $rol === "digitador") {
            echo '<a href="./lider.php">Mostrar Líderes</a>';
        }
        ?>
    </div>

    <div id="tablaVotantes" class="table-container">
        <table>
            <tr>
                <th>#</th>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Nombre Líder</th>
                <th>Barrio</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Puesto</th>
                <th>Mesa</th>
                <th>Creado Por</th>
            </tr>

            <?php
            if ($resultSearch->num_rows > 0) {
                // Inicializar el contador
                $contador = ($paginaActual - 1) * $eleccionesPorPagina + 1;

                // Mostrar los datos en filas de la tabla
                while ($row = $resultSearch->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $contador . "</td>
                        <td>" . $row['cedula'] . "</td>
                        <td>" . $row['nombre'] . "</td>
                        <td>" . $row['apellidos'] . "</td>
                        <td>" . $row['lider'] . "</td>
                        <td>" . $row['barrio'] . "</td>
                        <td>" . $row['direccion'] . "</td>
                        <td>" . $row['telefono'] . "</td>
                        <td>" . $row['puesto'] . "</td>
                        <td>" . $row['mesa'] . "</td>
                        <td>" . $row['creado_por'] . "</td>     
                    </tr>";

                    // Incrementar el contador
                    $contador++;
                }
            } else {
                echo "<tr><td colspan='10'>No se encontraron resultados.</td></tr>";
            }

            $conn->close();
            ?>
        </table>



    </div>
    <!-- Navegación de páginas 
    Copiar el codigo de navegacion-->

    <br>