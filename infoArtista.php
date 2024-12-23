<?php 
    session_start();
    $servername = "127.0.0.1";
    $database = "Metalized";
    $username = "alumno";
    $password = "alumnoipm";

    $conexion = mysqli_connect($servername, $username, $password, $database);
    if (!$conexion) {
        die("Conexion fallida: " . mysqli_connect_error());
    }
    else {
        if (isset($_GET['artista'])) {
            $a_id = $_GET['artista'];            

            
            $queryAlbum = "SELECT imagen, titulo FROM Album WHERE idArtista = ? ORDER BY titulo DESC";
            $stmt = $conexion->prepare($queryAlbum);
            $stmt->bind_param("i", $a_id);
            $stmt->execute();
            $resultadoA = $stmt->get_result();

            
            $queryArtista = "SELECT nombre, imagen, bio FROM Artista WHERE id = ?";
            $stmtArtista = $conexion->prepare($queryArtista);
            $stmtArtista->bind_param("i", $a_id);
            $stmtArtista->execute();
            $resultadoAr = $stmtArtista->get_result();

           
            $queryCancion = "SELECT Cancion.titulo, Cancion.duracion, Album.imagen FROM Usuario_escucha_Cancion 
            JOIN Cancion ON idCancion = Cancion.id
            JOIN Album ON idAlbum = Album.id
            JOIN Artista ON Album.idArtista = Artista.id
            WHERE Artista.id = ? 
            GROUP BY Cancion.titulo, Cancion.duracion, Album.imagen
            ORDER BY COUNT(*) DESC";
            $stmtCancion = $conexion->prepare($queryCancion);
            $stmtCancion->bind_param("i", $a_id);
            $stmtCancion->execute();
            $resultadoC = $stmtCancion->get_result();

            // Consulta para la canción actual
            $queryCanAct = "SELECT Cancion.titulo, Album.imagen, Artista.nombre FROM Cancion 
            JOIN Album ON idAlbum = Album.id 
            JOIN Artista ON idArtista = Artista.id
            WHERE Cancion.id = (
                SELECT idCancion FROM Usuario_escucha_Cancion 
                WHERE plays = CURRENT_DATE()
                )";
            $cancionActual = mysqli_query($conexion, $queryCanAct);
        } else {
            echo "No se ha proporcionado un ID de artista válido.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&display=swap');
    </style>
    <link rel="icon" type="image/png" href="calavera.png">
    <link rel="stylesheet" href="info-Artista.css" type="text/css"/>
    <title>Metalized</title>
</head>
<body>
    <header>
        <section id="contenedor">
            <div class="nyl">
                <img src="calavera.png">
                <h2>Metalized</h2>
            </div>
            <div class="menu">
                <ul>
                    <li id="inicio"><a href="metalized.php">Inicio</a></li>
                    <li id="descubre"><a href="descubre.php">Descubre</a></li>
                    <li id="mi_libreria"><p>Mi libreria</p>
                        <ul class="milibreria">
                            <li id="uno"><a href="canciones.php">Canciones</a></li>
                            <li id="dos"><a href="artistas.php">Artistas</a></li>
                            <li id="tres"><a href="albumes.php">Albumes</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </section>
    </header>

    <main>
        <div class="barra_horizontal">
            <div class="solapa">
                <h1>Metallica</h1>
            </div>
            <button class="seccionUsuario" onclick="cerrarSesion()">
                    <img src="ftPerfil.jpg" >
                    <h2><?php echo $_SESSION['usuario'] ?></h2>
                </button>

                    <script>
                    function cerrarSesion() {
                        // fetch para enviar una solicitud al servidor
                        fetch('cerrar_sesion.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded',},
                        })
                        .then(response => response.text())
                        .then(data => {
                            // si la sesión fue cerrada correctamente, redirige al usuario
                            window.location.href = 'login.html';
                        })
                        .catch(error => console.error('Error:', error));
                    }
                    </script>
            <div class="barraBusq">
                <h3>Buscar</h3>
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAXBJREFUSEu1lH1VA0EMxGcUgAUUAAoAB1QBVAGgAKqAooCigFZBWwdFAeCgVRDe8LK8Pbp7TT8u/93b2/1lkkmIjoMdv48iwMyOAdwBuAZw5kksAIwBvJBcRhNbA5jZLYBnAIKUQo8/kBxFIA2AP/7qFycAhiRn+jazSwBPAC78vEdSilrjD+Bl+fTM+7UMzUyQRwBScrKpXDkgXZyQVO2rYWZSJSUDkrpXjRygJp4CuEplqd3yck0BLEieRwGmH0mGrGtmof9zBaELKVsHrEjW3Pb7664lUo/eAcxJyl2hHqQmj0n2gk3WPAyjAEn9AnAkv5MclC5mNv3WlIdt6sOUpOtTVhRong2a/J9KstGijR5kzdOqkGwpKcXKzwWTtTXtb7UytS27e192mg3Fhy87Pbg0szQ3OhuR7JcgIc9XeiGlaW9VITsDvC/aqDdZAmtK9gJEIHsDCpDGJj4IIIPM/q/5gwG2smnb6G971rmCH/JPnxkOXXf0AAAAAElFTkSuQmCC" 
                    id="lupa" />
            </div>
        </div>
        
        <div id="artista">
            <?php while($fila = mysqli_fetch_assoc($resultadoAr)) { ?>
                <section class="fotoynombre">
                    <img src="<?php echo $fila['imagen']; ?>" alt="Imagen del artista">
                    <div class="info">
                        <h1 class="nombre"><?php echo $fila['nombre']; ?></h1>
                        <p><?php echo $fila['bio']; ?></p>
                    </div>
                </section>
            <?php } ?>

            <div class="contenedor2">
                <section id="canciones">
                    <h1>Populares</h1>
                    <?php while($fila = mysqli_fetch_assoc($resultadoC)) { ?>
                        <div class="cancion">
                            <img src="<?php echo $fila['imagen'] ?>">
                            <div class="info-popular">
                                <h3 class="tituloC"><?php echo $fila['titulo']; ?></h3>
                                <h3 class="duracion"><?php echo $fila['duracion']; ?></h3>
                            </div>
                        </div>
                    <?php } ?>
                </section>

                <section id="albumes">
                    <h1>Álbumes</h1>
                    <section id="gridAlbums">
                        <?php while($fila = mysqli_fetch_assoc($resultadoA)) { ?>
                            <div class="album">
                                <img src="<?php echo $fila['imagen']; ?>">
                                <h3 class="titulo"><?php echo $fila['titulo']; ?></h3>
                            </div>
                        <?php } ?>
                    </section>
                </section>
            </div>
        </div>
    </main>

<footer>
    <div id="imagenCancion">
            <img src="imagen.png">
            <div id="infoCancion">
                <h2>Peace sells</h2>
                <h3>Megadeth</h3>
            </div>
        </div>
        <section id="barraReproduccion">
            <div id="barraReproductora-iconos">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAK9JREFUSEvtle0NgzAMRB+TQTcpm3UU2KDdpJ2g1VWACB+ynQoVCfw3yT37LpCCjavYWJ/dAG7ANWda7wRv4A5cgGcEFAFIV+KCCOaqMaABStcpqAHZptJ005KWGklCXtq4xrMAg/Z4gh6wZFu/9gIqw6JEJwJ4dOJWyFmAyDXNAjizT0L/uuO16ATMvYt4Mtn7/5B/aD45OrtFkX+R1UTbfZT7eXCsjlfXve/BgQEfPOsqGYVSOv8AAAAASUVORK5CYII=" id="repetir"/>
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAALNJREFUSEvtlMERwjAMBDcVJB2EDkIJKYF0AJVAB6ED6ABKZMTAjElysj5+YX9P1snrsxsKr6Zwf6pBlvB/IeqAGzBtcJFaFNH4ab6DVfI8LRTTC3BOpk6H8rT3Fu8ENu0D2C+Q2B5P+ylXBkdgBoztcp0cbdXPM7gCrTBQWtjA+hqGJzAIREoLIUqLil1yamJRvAO9iKnSQjH9GtmFW6ODeGibWvShZT81VVANsugqoiyiFyspFBnP0GyFAAAAAElFTkSuQmCC" id="para-atras"/>
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAIFJREFUSEvtlUEOgCAQA8vDTfQ9+iB/o2kiN6XLmkYPcAV26CyBAvMo5voYAGn4c0UrgAnALo/6sEAlOK59M4AlA4kCWJspmGbrAfUAal0CwtoygAoKaXsDCGn7NYC3ipqaI5PA1mTrNQ3puHOlFNmfCtVDOa8SyAJqwQAoQ/5P/wRI2x4ZwEPsdgAAAABJRU5ErkJggg==" id="play-boton"/>
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAALBJREFUSEvtlNENhCAQRJ8dagdnB5bgdXId3JWgHViCduBV4GUTScieC0jCj5HfmeWFGaCi8KoK788NiCZ83YheQAesBxmEtD+7FdEGzEADTGoqpJ0COHMPPL1JAVhaFkCGBqDdT+UDtJYNkEHpQyDvg16c9tFaqAPt/QIPA+C0bMC4by7F64h8LSsiKViKdssHaO0UYAFq45paWjKg+EOLfmKphut+dqkJRH13RNGIfg4wMhkmyB+fAAAAAElFTkSuQmCC" id="para-adelante"/>
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAU1JREFUSEvVlNtRAkEQRQ8RSAaaAZoBRIBGgEaAZIIZYASYgYYgEYAZaARal+reasad2Vlr+bB/Z/eeftzuEWeO0Zn1+TeADXDf1o2hKvgG3oEZ8BlBQwKkewDuDHbkRICy8GjNJryPgVfguqUtqmAFqG1ZgN5ykJK48wRYpwB/lIDELy2LhyTLF2AOfFgFytir/wJugTf/JzcDla6PLhKIyl4AEpqGXguwM3HNoYnSkCPkCXg0mDKM4hL7s00lpGEq1CoJXZlbqo5AjU29LRFSJZ4bcvxZ7dgmalqmZohdpK4ZqD1ylWagWNqmCiKnefSegQbs4s/hzni7ZM0I6XUqlPHeMo/inq3vgex4YxX5HqTg4ibL13LRyfEysGYwKfTfHZcF5MTjtpcgrYAuQ5Te46k4WcKaPagB+6n41dKhAL1tWpN11TdDVZCF/QB4WlQZfSD04wAAAABJRU5ErkJggg==" id="aleatorio"/>
            </div>
            <div id="barraProgreso">
                <div class="progress">
                    <div class="progress-bar" style="width:75%;"></div>
                </div> 
            </div>
        </section>
</footer>

</body>
</html>
