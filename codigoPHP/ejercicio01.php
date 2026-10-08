<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>01</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
        <style>
            h5{
                color: blue;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Inicializar variables de los distintos tipos de datos básicos(string, int, float, bool) 
            y mostrar los datos por pantalla (echo, print, printf, print_r,var_dump).
            </h1>
        </header>

        <main>
            <?php
            $nombreProducto = "Ordenador";
            $numeroUnidades = 10;
            $precioProducto = 999.99;
            $productoDisponible = true;
            echo" <h5> echo permite mostrar texto y el contenido de una variable.</h5>";
           
            echo "La variable \$nombreProducto es de tipo " . gettype($nombreProducto) . " y tiene el valor " . $nombreProducto . "<br>";

            echo" print permite mostrar texto y el contenido de una variable.";
            print "La variable \$numeroUnidades es de tipo " . gettype($numeroUnidades) . " y tiene el valor " . $numeroUnidades . "<br>";

            echo" <h5> printf permite mostrar el texto dando formato a los valores.</h5>";
            printf(
                    "La variable \$precioProducto es de tipo %s y tiene el valor %.2f<br>",
                    gettype($precioProducto),
                    $precioProducto
            );

            echo" <h5> print_r permite mostrar el contenido de una variable de forma legible.</h5>";
            print_r(
                    "La variable \$productoDisponible es de tipo " . gettype($productoDisponible) . " y tiene el valor " .
                    ($productoDisponible ? "true" : "false")
            );
            echo "<br>";

            echo" <h5> var_dump muestra información detallada de la variable, incluyendo su tipo y su valor.</h5>";
            echo "La variable \$nombreProducto es de tipo ";
            var_dump($nombreProducto);

            echo "La variable \$numeroUnidades es de tipo ";
            var_dump($numeroUnidades);

            echo "La variable \$precioProducto es de tipo ";
            var_dump($precioProducto);

            echo "La variable \$productoDisponible es de tipo ";
            var_dump($productoDisponible);
            ?>
        </main>
        <footer>
            <p><a href="../indexProyectoTema3.php">Home</a></p>
            <div class="footer-text">
                <p>2026-27 IES Los Sauces. &copy; Todos los derechos reservados.<a href="../index.html">Yolanda Rodríguez Moreno</a></p>
                <time datetime="2026-09-30">Última actualización: 30 de Septiembre de 2026</time>
            </div>
        </footer>
    </body>
</html>
