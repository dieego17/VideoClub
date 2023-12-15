<?php
    //clase Pelicula
    include '../lib/model/pelicula.php';
    //clase Actor
    include '../lib/model/actor.php';
    //clase Usuario
    include '../lib/model/usuario.php';
    
    include '../pages/inicioSesion.php';
    
    
    // Verifica si la sesión está activa y si el usuario es normal
    if (!(isset($_SESSION['user']) && $_SESSION['rol'] === 0)) {
        // Si el usuario no es normal, cierra la sesión y redirige a la página de inicio de sesión
        session_unset();
        session_destroy();
        header("Location: ../index.php?error=3");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
    <!-- INICIO HEAD -->
    <head>
        <meta charset="UTF-8">
        <title>PELICULAS - VIDEOCLUB RUBIO</title>
        <link rel="shortcut icon" href="../assets/images/logo.jpeg" type="image/x-icon">
        <!-- Link to Bootstrap CSS library hosted on a CDN with integrity and crossorigin attributes -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <!-- LINK CSS -->
        <link rel="stylesheet" href="../css/inicio.css">
        <link rel="stylesheet" href="../css/inicioUser.css">
        <link rel="stylesheet" href="../css/header.css">
        <!-- link para favicons -->
    <script src="https://kit.fontawesome.com/3ed6284a33.js" crossorigin="anonymous"></script>
    </head>
    <!-- FIN HEAD -->

    <!-- INICIO BODY -->
    <body>
        <!-- HEADER -->
        <header class="header">
            <nav class="header__nav navbar navbar-expand-lg">
                <div class="container contenedor__nav">
                    <a class="navbar-brand" href="../pages/inicioUser.php">
                        <img class="header__img" src="../assets/images/titulo.png" alt="">
                        <p class="header__link">VideoClub Rubio</p>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="contenedor__ul collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav d-flex me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link active" aria-current="page" href="../pages/inicioUser.php">INICIO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../pages/peliculasUser.php">PELICULAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../pages/contactoUser.php">CONTACTO</a>
                            </li>
                        </ul>
                        <div class="contenedor__icon">
                            <form class="d-flex" role="search">
                                <a class="btn__icon btn" href="../pages/cerrarSesion.php" target="target">CERRAR SESIÓN</a>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <!-- FIN DEL HEADER -->
        <!-- IMAGEN INICIO -->
        <div class="div__inicio">
        </div>
        <div class="contenedor div__admin">
            <div class="container__text">
                <h1 class="principal__title">Bienvenido/a <?php echo ucfirst($name) ?></h1>
                <?php
                    if(isset($_COOKIE['ultimaVez'])){
                        echo "Tu última visita fue ".$_COOKIE['ultimaVez'];
                    }else{
                        echo "Esta es tu primera visita";
                    }
                ?>
            </div>
            <!-- INICIO SECTION -->
            <div class="reservas__section">
                <!-- INICIO TABLA -->
                <table class="table">
                    <thead>
                        <tr>
                            <td class="th__table" scope="col">Título</td>
                            <td class="th__table" scope="col">Género</td>
                            <td class="th__table" scope="col">País</td>
                            <td class="th__table" scope="col">Año</td>
                            <td class="th__table" scope="col">Cartel</td>
                            <td class="th__table th__table--foto" scope="col">Actor/Actriz</td>
                            <td class="th__table th__table--foto" scope="col">Actor/Actriz</td>
                            <td class="th__table th__table--foto" scope="col">Actor/Actriz</td>
                            <td class="th__table th__table--foto" scope="col">Actor/Actriz</td>
                            <td class="th__table th__table--foto" scope="col">Actor/Actriz</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $peliculas = consultaPeliculas();
                            if (count($peliculas) > 0) {
                                foreach ($peliculas as $pelicula) {
                                    echo "<tr>";
                                    echo "<td class='th__info'>" . $pelicula->getTitulo() . "</td>";
                                    echo "<td class='th__info'>" . $pelicula->getGenero() . "</td>";
                                    echo "<td class='th__info'>" . $pelicula->getPais() . "</td>";
                                    echo "<td class='th__info'>" . $pelicula->getAnyo() . "</td>";
                                    echo "<td> <img class='img__act' src='../assets/images/" . $pelicula->getCartel() . "'/> </td>";

                                    $actores = consultaActores($pelicula);
                                    if (count($actores) > 0) {
                                        foreach ($actores as $actor) {
                                            echo "<td class='th__info'><img class='img__act' src='../assets/images/". $actor->getFotografia() ."'/><br><br>".
                                                    $actor->getNombre() . " " . $actor->getApellidos() . "</td>";
                                        }
                                        
                                    } else {
                                        echo "<td class='th__info'>No hay actores</td>";
                                    }

                                    echo "</tr>";
                                    
                                }
                            } else {
                                echo "<tr>";
                                echo "<th scope='row' colspan='8'>No hay ninguna película</th>";
                                echo "</tr>";
                            }
                            ?>

                </table>
                <!-- FIN TABLA -->
            </div>
            <!-- FIN SECTION -->
        </div>
    </body>
    <!-- FIN BODY -->
</html>

