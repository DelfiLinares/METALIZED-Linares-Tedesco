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
    else{
        $query = 
        "SELECT Album.titulo, Album.imagen, Artista.nombre, COUNT(idCancion) AS rep, Artista.id as a_id FROM Album
        JOIN Artista ON Album.idArtista = Artista.id
        JOIN Cancion ON Album.id = Cancion.idAlbum
        JOIN Usuario_escucha_Cancion ON Cancion.id = idCancion
        WHERE Album.genero = 'Heavy Metal' OR Album.genero = 'Metal Progresivo'
        GROUP BY Album.titulo, Album.imagen, Artista.nombre, Artista.id
        ORDER BY rep DESC LIMIT 6;";

        $query2 = 
        "SELECT Artista.nombre, Artista.imagen, COUNT(idCancion) AS rep, Artista.id as a_id  FROM Artista
        JOIN Album ON Artista.id = Album.idArtista
        JOIN Cancion ON Album.id = Cancion.idAlbum
        JOIN Usuario_escucha_Cancion ON Cancion.id = Usuario_escucha_Cancion.idCancion
        WHERE Artista.genero = 'Heavy Metal' OR Artista.genero = 'Metal Progresivo'
        GROUP BY Artista.nombre, Artista.imagen, Artista.id
        ORDER BY rep DESC limit 3;";

        $resultado = mysqli_query($conexion, $query);
        $resultado2 = mysqli_query($conexion, $query2);
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style> @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap'); </style>
    <link rel="icon" type="image/png" href="calavera.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Descubre.css" type="text/css"/>
    <title>Metalized</title>
</head>

<body>
    <header>
        <section id="contenedor1">
            <div class="nyl">
                <img src="calavera.png">
                <h2>Metalized</h2>
            </div>

            <div class="menu">
                <ul>
                    <li class="inicio"><a href="metalized.php">Inicio</a></li>
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
                    <h2>Descubre</h2>
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
        
            <div id="inicio">
                <section id="generos">
                    <div class="opcion"><a href="descubre.php">Hard Rock</a></div>
                    <div class="opcion"><a href="descubre-rp.php">Rock Progresivo</a></div>
                    <div class="opcion"><a href="descubre-g.php">Grunge</a></div>
                    <div class="opcion_d"><a href="descubre-hm.php">Heavy Metal</a></div>
                </section>

                <div class="descripcion">
                    <h2>Albumes</h2>
                </div>
                <section id="albumes">
                <?php while($fila = mysqli_fetch_assoc($resultado)){ ?> 
                        <div class="contenedorAlbum">
                            <img src=<?php echo $fila['imagen']?>>
                            <p class="titulo"> <?php echo $fila['titulo']?> </p>
                        </div> 
                <?php } ?>
                </section>

                <div class="descripcion">
                        <h2>Artistas</h2>
                </div>

                <section id="artistas">
                    <?php while($fila = mysqli_fetch_assoc($resultado2)){ ?> 
                        <div class="contenedorArtista">
                            <img src=<?php echo $fila['imagen']?>>
                            <p class="artista"><a href="infoArtista.php?artista=<?php echo $fila['a_id'] ?>"><?php echo $fila['nombre']; ?> </a> </p>
                        </div> 
                    <?php } ?>
                </section>
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

    <script>
    let isPlaying = false;

    document.getElementById('play-boton').addEventListener('click', function() {
        isPlaying = !isPlaying; 

        if (isPlaying) {
            this.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAIFJREFUSEvtlUEOgCAQA8vDTfQ9+iB/o2kiN6XLmkYPcAV26CyBAvMo5voYAGn4c0UrgAnALo/6sEAlOK59M4AlA4kCWJspmGbrAfUAal0CwtoygAoKaXsDCGn7NYC3ipqaI5PA1mTrNQ3puHOlFNmfCtVDOa8SyAJqwQAoQ/5P/wRI2x4ZwEPsdgAAAABJRU5ErkJggg=='; // Pausa
        } else {
            this.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAERJREFUSEtjZKAxYKSx+QyjFhAM4ZERRP+RwgHmY2xiWIOLmCAatYBgShsNotEgwl8iE5PRCIYhPgWjFhAMvtEgIhhEAKEpFBmRjTAEAAAAAElFTkSuQmCC'; // Playing
        }
    });
    </script>
</body>
</html> 
