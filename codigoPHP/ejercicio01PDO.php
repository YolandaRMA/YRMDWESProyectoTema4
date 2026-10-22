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
            //require_once '../config/configDB00_home.php';
            require_once '../config/configDB00.php';
          
            // Se guarda en un array la lista de atributos que se van a consultar.
            $atributos = [
                "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION",
                "CONNECTION_STATUS", "ORACLE_NULLS", "PERSISTENT",
                "PREFETCH", "SERVER_INFO", "SERVER_VERSION", "TIMEOUT"
            ];

            echo "<h3>Conexión a la base de datos</h3>";

            try {
                // Se crea el objeto PDO usando el DSN, el usuario y la contraseña.
                $conexionPDO = new PDO(HOST, USER, PASS);

                // Se recorre el array para consultar cada atributo de la conexión.
                foreach ($atributos as $atributo) {
                    echo "PDO::ATTR_$atributo: ";

                    try {
                        // Se obtiene la constante PDO correspondiente al atributo.
                        $constante = constant("PDO::ATTR_$atributo");

                        // Se consulta y se muestra el valor del atributo.
                        echo $conexionPDO->getAttribute($constante) . "<br>";
                    } catch (PDOException $excepcion) {
                        // Se muestra el error si no se puede consultar el atributo.
                        echo "Error: " . $excepcion->getMessage() . "<br>";
                    }
                }
            } catch (PDOException $excepcion) {
                // Se informa del error si falla la conexión con la base de datos.
                echo "Código del error: " . $excepcion->getCode() . "<br>";
                echo "Mensaje: " . $excepcion->getMessage() . "<br>";
            } finally {
                // Se libera la referencia al objeto de conexión, si existe.
                unset($conexionPDO);
            }

            //atributos error PDO::ATTR_PREFETCH PDO::ATTR_TIMEOUT el controlador devuelve SQLSTATE[IM001], que indica que no admite esa operación o ese atributo.
//        // Se prueba una conexión utilizando datos incorrectos.
//            echo "<h3>Prueba de conexión incorrecta</h3>";
//
//            try {
//                // Se intenta conectar con un usuario y una contraseña no válidos.
//                $conexionIncorrecta = new PDO(
//                        "mysql:host=10.199.9.184;dbname=DBMDCDWESProyectoTema4",
//                        "usuarioIncorrecto",
//                        "paso"
//                );
//
//                echo "La conexión se ha realizado correctamente.";
//            } catch (PDOException $excepcion) {
//                // Se muestra el código y el mensaje del error producido.
//                echo "Código del error: " . $excepcion->getCode() . "<br>";
//                echo "Mensaje: " . $excepcion->getMessage() . "<br>";
//            }
            try {
                echo "<h3>Conexión incorrecta</h3>";

                // Se limita a 3 segundos la espera de conexión.
                $conexion = new PDO(
                        "mysql:host=10.199.9.184;dbname=DBMDCDWESProyectoTema4",
                        "usuarioIncorrecto",
                        "paso",
                        [PDO::ATTR_TIMEOUT => 3]
                );
            } catch (PDOException $exception) {
                echo "Código del error: " . $exception->getCode() . "<br>";
                echo "Mensaje de error: " . $exception->getMessage() . "<br>";
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
