<?php
    //clase Pelicula
    include '../lib/model/pelicula.php';
    //clase Actor
    include '../lib/model/actor.php';
    //clase Usuario
    include '../lib/model/usuario.php';
    
    include '../pages/inicioSesion.php';

    // verifica si la sesión está activa y si el usuario es administrador
    if (!(isset($_SESSION['user']) && $_SESSION['rol'] === 1)) {
        // si el usuario no es administrador, cierra la sesión y redirige a la página de inicio de sesión
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
        <title>INICIO - VIDEOCLUB RUBIO</title>
        <link rel="shortcut icon" href="../assets/images/logo.jpeg" type="image/x-icon">
        <!-- Link to Bootstrap CSS library hosted on a CDN with integrity and crossorigin attributes -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <!-- LINK CSS -->
        <link rel="stylesheet" href="../css/inicio.css">
        <link rel="stylesheet" href="../css/inicioAdmin.css">
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
                    <a class="navbar-brand" href="../pages/inicioAdmin.php">
                        <img class="header__img" src="../assets/images/titulo.png" alt="">
                        <p class="header__link">VideoClub Rubio</p>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="contenedor__ul collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav d-flex me-auto mb-2 mb-lg-0">
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
        <div class="contenedor div__admin">
            <div class="container__text">
                <h1 class="principal__title">Bienvenido/a <?php echo ucfirst($name) ?></h1>
                <?php
                    if(isset($_COOKIE['fecha'])){
                        echo "Tu última visita fue ".$_COOKIE['fecha'];
                    }else{
                        echo "Esta es tu primera visita";
                    }
                ?>
            </div>
            <div class="container__button">
                <button type="button" class="btn btn-success">Añadir Nueva Pelicula</button>
            </div>
            <!-- INICIO SECTION -->
            <div class="reservas__section">
                <!-- INICIO TABLA -->
                <table class="table">
                    <thead>
                        <tr>
                            <th class="th__table" scope="col">Id Película</th>
                            <th class="th__table" scope="col">Titulo</th>
                            <th class="th__table" scope="col">Género</th>
                            <th class="th__table" scope="col">País</th>
                            <th class="th__table" scope="col">Año</th>
                            <th class="th__table" scope="col">Cartel</th>
                            <th class="th__table" scope="col">Id Actor</th>
                            <th class="th__table" scope="col">Nombre Actor</th>
                            <th class="th__table" scope="col">Apellido Actor</th>
                            <th class="th__table" scope="col">Fotografía</th>
                            <th class="th__table" scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $peliculas = consultaPeliculas();
                            if (count($peliculas) > 0) {
                                foreach ($peliculas as $pelicula) {
                                    $actores = consultaActores($pelicula);  
                                    
                                    if(count($actores)>0){
                                        foreach($actores as $actor){
                                        echo "<tr>";
                                        echo "<td class='th__info'>" . $pelicula->getId() . "</td>";
                                        echo "<td class='th__info'>" . $pelicula->getTitulo() . "</td>";
                                        echo "<td class='th__info'>" . $pelicula->getGenero() . "</td>";
                                        echo "<td class='th__info'>" . $pelicula->getPais() . "</td>";
                                        echo "<td class='th__info'>" . $pelicula->getAnyo() . "</td>";
                                        echo "<td> <img width='100px' src='../assets/images/" . $pelicula->getCartel() . "'/> </td>";
                                        echo "<td class='th__info'>" . $actor->getId() . "</td>";
                                        echo "<td class='th__info'>" . $actor->getNombre() . "</td>";
                                        echo "<td class='th__info'>" . $actor->getApellidos() . "</td>";
                                        echo "<td><img width='100px' src='../assets/images/" . $actor->getFotografia() . "'/> </td>";
                                        echo "<td>
                                            <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                                                <i class='fa-solid fa-x eliminar'></i>
                                            </button>
                                            <div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h1 class='modal-title fs-5' id='exampleModalLabel'>Eliminar Pelicula</h1>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                        </div>
                                                        <div class='modal-body'>¿Esta seguro que quiere elimanar la pelicula seleccionada? Si esta seguro pulse confirmar, si no
                                                            lo esta pulse cancelar.
                                                        </div>
                                                        <div class='modal-footer'>
                                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                                        <a href='../pages/eliminarPelicula.php?id=" . $pelicula->getId() . "' class='btn btn-primary'>Confirmar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                                                        <i class='fa-solid fa-pen eliminar'></i>
                                            </button></td>";
                                        echo "</tr>";
                                        }
                                    }else{
                                        echo "<tr>";
                                        echo "<th scope='row' colspan='4'>No hay ningun actor</th>";
                                        echo "</tr>";
                                    }
                                }
                                
                            } else {
                                echo "<tr>";
                                echo "<th scope='row' colspan='4'>No hay ninguna pelicula</th>";
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

