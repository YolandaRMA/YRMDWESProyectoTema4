<html>
    <head>
        <meta charset="UTF-8">
        <title>04-01</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">

        <style>
            /* Se establece el aspecto de la tabla del formulario. */
            .tablaFormulario {
                width: 100%;
                border-collapse: collapse;
            }

            .tablaFormulario td {
                padding: 10px;
                border: 1px solid #ccc;
            }

            /* Se identifican los campos obligatorios mediante el color amarillo. */
            .obligatorio {
                background-color: #fff2cc;
            }

            /* Se identifican los campos opcionales mediante el color blanco. */
            .opcional {
                background-color: white;
            }

            /* Se muestran los mensajes de error en rojo. */
            .error {
                color: red;
                display: block;
                margin-top: 4px;
            }

            /* Se diferencia la fila de los botones. */
            .botones {
                text-align: center;
            }

            /* Se establece el aspecto de la tabla de departamentos. */
            .tablaDepartamentos {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .tablaDepartamentos th {
                background-color: #555;
                color: white;
                padding: 10px;
                text-align: left;
            }

            .tablaDepartamentos td {
                padding: 10px;
                border-bottom: 1px solid #ccc;
            }

            /* Se alterna el color de las filas para facilitar la lectura. */
            .tablaDepartamentos tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <header>Alta de departamentos</header>

        <main>
            <?php
            // Se incluyen las constantes con los datos de conexión.
            require_once '../config/configDB00_home.php';

            // Se carga la librería que contiene las funciones de validación.
            require_once '../core/231018libreriaValidacion.php';

            // Se inicializan las variables que recogerán los datos del formulario.
            $codigoDepartamento = '';
            $descripcionDepartamento = '';
            $fechaCreacionDepartamento = '';
            $volumenDeNegocio = '';
            $fechaBajaDepartamento = '';

            // Se inicializa el array que almacenará los posibles errores.
            $aErrores = [
                'codigoDepartamento' => '',
                'descripcionDepartamento' => '',
                'fechaCreacionDepartamento' => '',
                'volumenDeNegocio' => '',
                'fechaBajaDepartamento' => ''
            ];

            // Se inicializa la variable que indica si los datos son correctos.
            $entradaOK = true;

            // Se comprueba si se ha enviado el formulario.
            if (isset($_POST['enviar'])) {

                // Se recogen los datos introducidos por el usuario.
                $codigoDepartamento = $_POST['codigoDepartamento'];
                $descripcionDepartamento = $_POST['descripcionDepartamento'];
                $fechaCreacionDepartamento = $_POST['fechaCreacionDepartamento'];
                $volumenDeNegocio = $_POST['volumenDeNegocio'];
                $fechaBajaDepartamento = $_POST['fechaBajaDepartamento'];

                // Se comprueba el código: recibe el texto, tamaño máximo, mínimo y obligatoriedad.
                // Devuelve null si es correcto o un mensaje de error.
                $aErrores['codigoDepartamento'] = validacionFormularios::comprobarAlfaNumerico($codigoDepartamento, 3, 3, 1);

                // Se comprueba la descripción: recibe el texto, tamaño máximo, mínimo y obligatoriedad.
                // Devuelve null si es correcta o un mensaje de error.
                $aErrores['descripcionDepartamento'] = validacionFormularios::comprobarAlfabetico($descripcionDepartamento, 100, 1, 1);

                // Se comprueba la fecha de creación: recibe la fecha, fecha máxima, mínima y obligatoriedad.
                // Devuelve null si es correcta o un mensaje de error.
                $aErrores['fechaCreacionDepartamento'] = validacionFormularios::validarFecha($fechaCreacionDepartamento, date('Y-m-d'), '1900-01-01', 1);

                // Se comprueba el volumen de negocio: recibe el valor, máximo, mínimo y obligatoriedad.
                // Devuelve null si es correcto o un mensaje de error.
                $aErrores['volumenDeNegocio'] = validacionFormularios::comprobarFloat($volumenDeNegocio, PHP_FLOAT_MAX, 0, 1);

                // Se comprueba la fecha de baja, que es opcional.
                // Recibe la fecha, fecha máxima, mínima y obligatoriedad.
                // Devuelve null si es correcta o un mensaje de error.
                $aErrores['fechaBajaDepartamento'] = validacionFormularios::validarFecha($fechaBajaDepartamento, date('Y-m-d'), '1900-01-01', 0);

                // Se recorren los errores para comprobar si existe alguno.
                foreach ($aErrores as $error) {
                    if (!empty($error)) {
                        $entradaOK = false;
                    }
                }
            } else {
                // Si todavía no se ha enviado el formulario, se muestra vacío.
                $entradaOK = false;
            }

            // Se comprueba si todos los datos son correctos para realizar la inserción.
            if ($entradaOK) {
                try {
                    // Se establece la conexión con la base de datos.
                    $conexionPDO = new PDO(HOST, USER, PASS);

                    // Se prepara la fecha de baja para insertar NULL si se ha dejado vacía.
                    $fechaBajaSQL = empty($fechaBajaDepartamento) ? 'NULL' : "'$fechaBajaDepartamento'";

                    // Se construye la consulta SQL de inserción.
                    $consultaSQL = "INSERT INTO T02_Departamento
                        (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio, T02_FechaBajaDepartamento)
                        VALUES
                        ('$codigoDepartamento', '$descripcionDepartamento', '$fechaCreacionDepartamento', $volumenDeNegocio, $fechaBajaSQL)";

                    // Se ejecuta la consulta de inserción.
                    if ($conexionPDO->query($consultaSQL)) {

                        // Se muestra el mensaje de confirmación.
                        echo '<p>El departamento se ha añadido correctamente.</p>';

                        // Se consultan todos los departamentos de la tabla.
                        $consultaDepartamentos = $conexionPDO->query("SELECT * FROM T02_Departamento");

                        // Se comprueba si existen departamentos para mostrar.
                        if ($consultaDepartamentos->rowCount() > 0) {

                            // Se muestra la tabla con los departamentos.
                            echo '<table class="tablaDepartamentos">';

                            // Se muestran los encabezados de la tabla.
                            echo '<tr>';
                            echo '<th>Código</th>';
                            echo '<th>Descripción</th>';
                            echo '<th>Fecha de creación</th>';
                            echo '<th>Volumen de negocio</th>';
                            echo '<th>Fecha de baja</th>';
                            echo '</tr>';

                            // Se recorren los departamentos obtenidos mediante la consulta.
                            while ($departamento = $consultaDepartamentos->fetch(PDO::FETCH_ASSOC)) {

                                // Se muestra cada departamento en una fila.
                                echo '<tr>';
                                echo '<td>' . $departamento['T02_CodDepartamento'] . '</td>';
                                echo '<td>' . $departamento['T02_DescDepartamento'] . '</td>';
                                echo '<td>' . $departamento['T02_FechaCreacionDepartamento'] . '</td>';
                                echo '<td>' . number_format($departamento['T02_VolumenDeNegocio'], 2, ',', '.') . '</td>';

                                // Se muestra un guion si el departamento no tiene fecha de baja.
                                echo '<td>' . ($departamento['T02_FechaBajaDepartamento'] ?? '-') . '</td>';
                                echo '</tr>';
                            }

                            // Se cierra la tabla.
                            echo '</table>';
                        }
                    }

                } catch (PDOException $miExcepcionPDO) {
                    // Se muestra el mensaje si se produce un error durante la conexión o inserción.
                    echo '<p class="error">Error al insertar el departamento: ' . $miExcepcionPDO->getMessage() . '</p>';
                } finally {
                    // Se libera la conexión con la base de datos.
                    unset($conexionPDO);
                }
            }

            // Se muestra el formulario cuando todavía no se ha enviado o existen errores.
            if (!$entradaOK) {
            ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <table class="tablaFormulario">
                        <tr>
                            <td><label for="codigoDepartamento">Código del departamento:</label></td>
                            <td class="obligatorio">
                                <input type="text" name="codigoDepartamento" id="codigoDepartamento" maxlength="3" value="<?php echo $codigoDepartamento; ?>">
                                <span class="error"><?php echo $aErrores['codigoDepartamento']; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="descripcionDepartamento">Descripción:</label></td>
                            <td class="obligatorio">
                                <input type="text" name="descripcionDepartamento" id="descripcionDepartamento" value="<?php echo $descripcionDepartamento; ?>">
                                <span class="error"><?php echo $aErrores['descripcionDepartamento']; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="fechaCreacionDepartamento">Fecha de creación:</label></td>
                            <td class="obligatorio">
                                <input type="date" name="fechaCreacionDepartamento" id="fechaCreacionDepartamento" value="<?php echo $fechaCreacionDepartamento; ?>">
                                <span class="error"><?php echo $aErrores['fechaCreacionDepartamento']; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="volumenDeNegocio">Volumen de negocio:</label></td>
                            <td class="obligatorio">
                                <input type="number" step="any" name="volumenDeNegocio" id="volumenDeNegocio" value="<?php echo $volumenDeNegocio; ?>">
                                <span class="error"><?php echo $aErrores['volumenDeNegocio']; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="fechaBajaDepartamento">Fecha de baja (opcional):</label></td>
                            <td class="opcional">
                                <input type="date" name="fechaBajaDepartamento" id="fechaBajaDepartamento" value="<?php echo $fechaBajaDepartamento; ?>">
                                <span class="error"><?php echo $aErrores['fechaBajaDepartamento']; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="botones">
                                <input type="submit" value="Añadir departamento" name="enviar">
                            </td>
                        </tr>
                    </table>
                </form>
            <?php
            }
            ?>
        </main>

        <footer>
            <p><a href="../indexProyectoTema4.php">Home</a></p>
            <div class="footer-text">
                <p>2026-27 IES Los Sauces. &copy; Todos los derechos reservados.<a href="../../index.html"> Yolanda Rodríguez Moreno</a></p>
                <time datetime="2026-11-03">Última actualización: 03 de Noviembre de 2026</time>
            </div>
        </footer>
    </body>
</html>