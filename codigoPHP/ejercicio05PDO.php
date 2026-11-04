<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Ejercicio 05</title>
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

                // Se inicia la transacción para que las tres inserciones se realicen conjuntamente.
                $conexionPDO->beginTransaction();

                // Primera instrucción INSERT: se añade el primer departamento.
                $consultaSQL1 = "INSERT INTO T02_Departamento
                                VALUES ('KMS', 'Departamento prueba 1', NOW(), 10000, NULL)";
                $conexionPDO->exec($consultaSQL1);

                // Segunda instrucción INSERT: se añade el segundo departamento.
                $consultaSQL2 = "INSERT INTO T02_Departamento
                                VALUES ('AAA', 'Departamento prueba 2', NOW(), 7658.40, NULL)";
                $conexionPDO->exec($consultaSQL2);

                // Tercera instrucción INSERT: se añade el tercer departamento.
                $consultaSQL3 = "INSERT INTO T02_Departamento
                                VALUES ('WWE', 'Departamento de wrestling', NOW(), 70546.90, NULL)";
                $conexionPDO->exec($consultaSQL3);

                // Se confirman los cambios cuando las tres inserciones han terminado correctamente.
                $conexionPDO->commit();

                echo '<p class="mensajeExito">Transacción completada. Los tres departamentos se han añadido correctamente.</p>';

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
                // Se deshacen las inserciones si la transacción sigue activa.
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