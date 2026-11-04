<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Ejercicio 04</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
        <style>
            .tablaFormulario,
            .tablaDepartamentos {
                border-collapse: collapse;
                margin: 20px auto;
                width: 90%;
                max-width: 900px;
            }

            .tablaFormulario td,
            .tablaDepartamentos th,
            .tablaDepartamentos td {
                border: 1px solid #ccc;
                padding: 10px;
            }

            .tablaFormulario td:first-child {
                background-color: #fff2cc;
                font-weight: bold;
            }

            .tablaFormulario input[type="text"] {
                padding: 7px;
                width: 95%;
            }

            .tablaDepartamentos th {
                background-color: #343a40;
                color: white;
            }

            .tablaDepartamentos tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            .tablaDepartamentos td {
                text-align: left;
            }

            .boton {
                padding: 8px 18px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Conexión y gestión de errores</h1>
        </header>

        <main>
            <?php
            // Se incluyen las constantes de conexión a la base de datos.
            require_once '../config/configDB00.php';

            // Se inicializa el texto que se utilizará para buscar.
            $descripcionBuscada = '';

            // Se comprueba si se ha enviado el formulario.
            if (isset($_POST['buscar'])) {
                $descripcionBuscada = trim($_POST['descripcionDepartamento']);
            }

            try {
                // Se crea el objeto PDO para conectar con la base de datos.
                $conexionPDO = new PDO(HOST, USER, PASS);

                // Se configura PDO para mostrar las excepciones cuando se produzca un error.
                $conexionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Se comprueba si el usuario ha introducido una descripción.
                if ($descripcionBuscada == '') {
                    // Si la búsqueda está vacía, se seleccionan todos los departamentos.
                    $consultaSQL = 'SELECT * FROM T02_Departamento ORDER BY T02_CodDepartamento';
                } else {
                    // Se protege el texto introducido antes de incorporarlo a la consulta SQL.
                    $descripcionSQL = $conexionPDO->quote('%' . $descripcionBuscada . '%');

                    // Se buscan las descripciones que contienen el texto introducido.
                    $consultaSQL = 'SELECT * FROM T02_Departamento
                                    WHERE T02_DescDepartamento LIKE ' . $descripcionSQL . '
                                    ORDER BY T02_CodDepartamento';
                }

                // Se ejecuta la consulta SQL.
                $resultadoConsulta = $conexionPDO->query($consultaSQL);

                echo '<h2>Buscar departamentos</h2>';

                // Se muestra el formulario de búsqueda.
                echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
                echo '<table class="tablaFormulario">';
                echo '<tr>';
                echo '<td><label for="descripcionDepartamento">Descripción del departamento:</label></td>';
                echo '<td><input type="text" id="descripcionDepartamento" name="descripcionDepartamento" value="' . $descripcionBuscada . '"></td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td colspan="2"><input class="boton" type="submit" name="buscar" value="Buscar"></td>';
                echo '</tr>';
                echo '</table>';
                echo '</form>';

                // Se muestra el resultado de la búsqueda.
                if ($descripcionBuscada == '') {
                    echo '<h2>Todos los departamentos</h2>';
                } else {
                    echo '<h2>Resultados de la búsqueda</h2>';
                }

                // Se abre la tabla que contiene los departamentos.
                echo '<table class="tablaDepartamentos">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Código</th>';
                echo '<th>Descripción</th>';
                echo '<th>Fecha de creación</th>';
                echo '<th>Volumen de negocio</th>';
                echo '<th>Fecha de baja</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // Se recorren los departamentos obtenidos en la consulta.
                $numeroDepartamentos = 0;

                while ($departamento = $resultadoConsulta->fetch(PDO::FETCH_ASSOC)) {
                    $numeroDepartamentos++;

                    echo '<tr>';
                    echo '<td>' . $departamento['T02_CodDepartamento'] . '</td>';
                    echo '<td>' . $departamento['T02_DescDepartamento'] . '</td>';

                    // Se convierte la fecha de creación al formato día/mes/año.
                    $fechaCreacion = new DateTime($departamento['T02_FechaCreacionDepartamento']);
                    echo '<td>' . $fechaCreacion->format('d/m/Y') . '</td>';

                    // Se muestra el volumen de negocio con dos decimales.
                    echo '<td>' . number_format($departamento['T02_VolumenDeNegocio'], 2, ',', '.') . ' €</td>';

                    // Se comprueba si el departamento tiene fecha de baja.
                    if ($departamento['T02_FechaBajaDepartamento'] == null) {
                        echo '<td>-</td>';
                    } else {
                        $fechaBaja = new DateTime($departamento['T02_FechaBajaDepartamento']);
                        echo '<td>' . $fechaBaja->format('d/m/Y') . '</td>';
                    }

                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';

                // Se informa del número de departamentos encontrados.
                echo '<p>Departamentos encontrados: ' . $numeroDepartamentos . '</p>';

            } catch (PDOException $excepcionPDO) {
                // Se muestra el código y el mensaje del error producido.
                echo '<p>Error de conexión o consulta.</p>';
                echo '<p>Código: ' . $excepcionPDO->getCode() . '</p>';
                echo '<p>Mensaje: ' . $excepcionPDO->getMessage() . '</p>';
            } finally {
                // Se libera la conexión con la base de datos.
                unset($conexionPDO);
            }
            ?>
        </main>

        <footer>
            <p>2026-27 IES Los Sauces. © Todos los derechos reservados.
                <a href="../../index.html">Yolanda Rodríguez Moreno</a>
            </p>
            <time datetime="2026-11-04">Última actualización: 04 de Noviembre de 2026</time>
        </footer>
    </body>
</html>