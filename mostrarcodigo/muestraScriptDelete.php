<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>delete</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
    </head>
    <body>
       <header>
            <h1>Borrado de base de datos y usuario. </h1>
        </header>
        <main>
            <?php
            $archivo = '../scriptDB/BorraDBYRMDWESProyectoTema4.sql';

            if ($archivo && file_exists($archivo)) {               
                
                highlight_file($archivo);
            } else {
                echo "<h3>El archivo no existe.</h3>";              
            }
            ?>
        </main>
        <footer>
              <p><a href="../indexProyectoTema4.php">Home</a></p>
            <div class="footer-text">
                <p>2026-27 IES Los Sauces. &copy; Todos los derechos reservados.<a href="../index.html">Yolanda Rodríguez Moreno</a></p>
                <time datetime="2026-09-30">Última actualización: 30 de Septiembre de 2026</time>
            </div>
        </footer>
    </body>
</html>