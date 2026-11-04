<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Ejercicio 06</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
        <style>
            .tablaDepartamentos {
                border-collapse: collapse;
                margin: 20px auto;
                width: 90%;
                max-width: 900px;
            }

            .tablaDepartamentos th,
            .tablaDepartamentos td {
                border: 1px solid #ccc;
                padding: 10px;
            }

            .tablaDepartamentos th {
                background-color: #343a40;
                color: white;
            }

            .tablaDepartamentos tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            .mensajeExito {
                color: #155724;
                background-color: #d4edda;
                padding: 12px;
                margin: 15px auto;
                max-width: 900px;
            }

            .mensajeError {
                color: #721c24;
                background-color: #f8d7da;
                padding: 12px;
                margin: 15px auto;
                max-width: 900px;
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

            try {
                // Se crea el objeto PDO para conectar con la base de datos.
                $conexionPDO = new PDO(HOST, USER, PASS);

                // Se configura PDO para mostrar las excepciones cuando se produzca un error.
                $conexionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Se crea un array con los tres departamentos que se van a insertar.
                $aDepartamentosNuevos = [
                    [
                        ':codigoDepartamento' => 'NFS',
                        ':descripcionDepartamento' => 'Departamento Need for Speed',
                        ':volumenDeNegocio' => 15000.50
                    ],
                    [
                        ':codigoDepartamento' => 'PRA',
                        ':descripcionDepartamento' => 'Departamento PR Acho',
                        ':volumenDeNegocio' => 12345.00
                    ],
                    [
                        ':codigoDepartamento' => 'TST',
                        ':descripcionDepartamento' => 'Departamento de testeo',
                        ':volumenDeNegocio' => 98765.43
                    ]
                ];

                // Se inicia la transacción para insertar todos los departamentos conjuntamente.
                $conexionPDO->beginTransaction();

                // Se prepara la consulta SQL utilizando parámetros con nombres descriptivos.
                $consultaSQL = 'INSERT INTO T02_Departamento
                                (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio, T02_FechaBajaDepartamento)
                                VALUES (:codigoDepartamento, :descripcionDepartamento, NOW(), :volumenDeNegocio, NULL)';

                // Se prepara la sentencia SQL para poder ejecutarla varias veces.
                $sentenciaPreparada = $conexionPDO->prepare($consultaSQL);

                // Se recorre el array para insertar cada departamento.
                foreach ($aDepartamentosNuevos as $departamento) {

                    // Se ejecuta la consulta preparada pasando los valores mediante un array asociativo.
                    $sentenciaPreparada->execute($departamento);

                    echo '<p>Departamento añadido: <strong>' . $departamento[':codigoDepartamento'] . '</strong></p>';
                }

                // Se confirman los cambios cuando todos los departamentos se han insertado correctamente.
                $conexionPDO->commit();

                echo '<p class="mensajeExito">Transacción completada. Todos los departamentos se han añadido correctamente.</p>';

                // Se seleccionan todos los departamentos para mostrar el resultado.
                $consultaDepartamentos = $conexionPDO->query('SELECT * FROM T02_Departamento ORDER BY T02_CodDepartamento');

                echo '<h2>Departamentos registrados</h2>';

                // Se muestra la tabla con los departamentos.
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
                while ($departamento = $consultaDepartamentos->fetch(PDO::FETCH_ASSOC)) {
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

            } catch (PDOException $excepcionPDO) {
                // Se deshacen los cambios si la transacción sigue activa.
                if (isset($conexionPDO) && $conexionPDO->inTransaction()) {
                    $conexionPDO->rollBack();
                }

                echo '<p class="mensajeError">Transacción cancelada. No se han guardado los cambios.</p>';
                echo '<p>Código del error: ' . $excepcionPDO->getCode() . '</p>';
                echo '<p>Mensaje del error: ' . $excepcionPDO->getMessage() . '</p>';

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