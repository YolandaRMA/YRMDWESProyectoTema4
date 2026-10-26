<html>
    <head>
        <meta charset="UTF-8">
        <title>04-01</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
    </head>
    <body>
        <header>Conexión y gestión de errores.</header>

        <main>
            <?php
            // Se incluyen las constantes con los datos de conexión.
            require_once '../config/configDB00_home.php';

            echo "<h3>Consulta de departamentos</h3>";

            try {
                // Se establece la conexión con la base de datos mediante PDO.
                $conexionPDO = new PDO(HOST, USER, PASS);

                // Se ejecuta la consulta para obtener todos los departamentos.
                $consulta = $conexionPDO->query(
                        "SELECT * FROM T02_Departamento"
                );

                // Se obtiene el número de registros devueltos por la consulta.
                $numeroRegistros = $consulta->rowCount();

                // Se muestra la tabla con los nombres de las columnas.
                echo "<table>";
                echo "<tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Fecha de creación</th>
                <th>Volumen de negocio</th>
                <th>Fecha de baja</th>
              </tr>";

                // Se recorre el resultado y se obtiene cada departamento como objeto.
                while ($departamento = $consulta->fetchObject()) {

                    echo "<tr>";

                    // Se muestra el código y la descripción del departamento.
                    // se utiliza la funcion htmlspecialchars para evitar que datos introducidos por usuarios se interpreten como código HTML o JavaScrip
                    //en una aplicación real, es recomendable conservar htmlspecialchars() al mostrar datos que pueden proceder de usuarios.
                    echo "<td>" . htmlspecialchars($departamento->T02_CodDepartamento) . "</td>";
                    echo "<td>" . htmlspecialchars($departamento->T02_DescDepartamento) . "</td>";

                    // Se convierte la fecha de creación al formato español.
                    $fechaCreacion = new DateTime($departamento->T02_FechaCreacionDepartamento);
                    echo "<td>" . $fechaCreacion->format('d/m/Y') . "</td>";

                    // Se muestra el volumen de negocio con dos decimales y formato español.
                    echo "<td>" . number_format($departamento->T02_VolumenDeNegocio, 2, ',', '.') . " €</td>";

                    // Se muestra un guion si no existe fecha de baja.
                    if ($departamento->T02_FechaBajaDepartamento === null) {
                        echo "<td>-</td>";
                    } else {
                        // Se convierte la fecha de baja al formato español.
                        $fechaBaja = new DateTime($departamento->T02_FechaBajaDepartamento);
                        echo "<td>" . $fechaBaja->format('d/m/Y') . "</td>";
                    }

                    echo "</tr>";
                }

                echo "</table>";

                // Se muestra el número total de departamentos consultados.
                echo "<p>Número de registros: " . $numeroRegistros . "</p>";
            } catch (PDOException $excepcion) {
                // Se muestra el código y el mensaje si ocurre un error con la base de datos.
                echo "Código del error: " . $excepcion->getCode() . "<br>";
                echo "Mensaje de error: " . $excepcion->getMessage();
            } finally {
                // Se libera la conexión con la base de datos.
                unset($conexionPDO);
            }
            ?>
        </main>
        <footer>
            <p><a href="../indexProyectoTema4.php">Home</a></p>
            <div class="footer-text">
                <p>2026-27 IES Los Sauces. &copy; Todos los derechos reservados.<a href="../../index.html"> Yolanda Rodríguez Moreno</a></p>                <time datetime="2026-09-30">Última actualización: 30 de Septiembre de 2026</time>
            </div>
        </footer>
    </body>
</html>
