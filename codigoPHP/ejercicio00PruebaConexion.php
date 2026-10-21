<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>prueba</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
    </head>
    <body>
        <main>
            <header>Prueba de conexión </header>

            <?php
            echo "1. Inicio del ejercicio<br>";
            // Se carga el archivo con los datos de conexión.
            require_once '../config/configDB00_home.php';
            echo "2. Configuración cargada<br>";

            try {
                // Se establece la conexión con la base de datos.
                // Se limita la espera de conexión a 3 segundos.
                $conexionPDO = new PDO(HOST, USER, PASS, [
                    PDO::ATTR_TIMEOUT => 3,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                echo "3. Conexión realizada correctamente<br>";

                // Se obtiene la lista de tablas de la base de datos.
                $tablas = $conexionPDO->query("SHOW TABLES");

                // Se recorre cada tabla encontrada.
                while ($tabla = $tablas->fetch(PDO::FETCH_NUM)) {
                    $nombreTabla = $tabla[0];

                    echo "<h3>Tabla: " . htmlspecialchars($nombreTabla) . "</h3>";

                    // Se consultan todas las columnas y filas de la tabla.
                    $consulta = $conexionPDO->query("SELECT * FROM `$nombreTabla`");

                    // Se muestra el contenido de cada fila.
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        foreach ($fila as $columna => $valor) {
                            echo "<strong>" . htmlspecialchars($columna) . ":</strong> ";
                            echo htmlspecialchars((string) $valor) . " | ";
                        }

                        echo "<br>";
                    }
                }
            } catch (PDOException $excepcion) {
                echo "Error de conexión o consulta: " . $excepcion->getMessage();
            } finally {
                // Se libera la referencia al objeto de conexión.
                unset($conexionPDO);
            }
            ?>
            <!--        Si aparece «1. Inicio del ejercicio» , revisar la carga de configDB00.php.
                    Si aparece «2. Configuración cargada» y se queda esperando, el problema probablemente está en la conexión con MariaDB.
                    **PHP se queda bloqueado al intentar establecer la conexión con MariaDB. No llega al mensaje 3.
                    Si aparece el error de conexión,información para solucionarlo.
                    Si aparece «3. Conexión realizada correctamente», revisar el bucle de atributos.-->
        </main>
        <footer>
            <p><a href="../indexProyectoTema4.php">Home</a></p>
            <div class="footer-text">
                <p>2026-27 IES Los Sauces. &copy; Todos los derechos reservados.<a href="../../index.html"> Yolanda Rodríguez Moreno</a></p>                <time datetime="2026-09-30">Última actualización: 30 de Septiembre de 2026</time>
            </div>
        </footer>
    </body>
</html>
