<?php
    //para incluir las funciones que haya en functions.php
    include '../lib/functions/functions.php';
    //inicio de la sesion
    session_start();
    //comprobamos que no existe la sesion user
    if (!isset($_SESSION['user'])) {
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);
            //comprobamos que los campos username y password no esten vacios
            if ($username != "" && $password != "") {
                //ciframos la contraseña
                $pass_hash = hash("sha256", $password);
                echo $pass_hash;
                //nos conectamos a la base de datos
                $bd = conexionBD();
                //guardamos la sentencia sql para luego utilizarla
                $users = consultaLogin($username);
                if (gettype($users) == null) {
                    header('Location: ../index.php?error=2');
                } else {
                    $user=$users->rowCount();
                }

                //Comprueba que la consulta nos ha devuelto solo una 1 fila que es la que indica que no hay ningun usuario repetido.
                if ($user == 1) {
                    $name;
                    $hashed_password;
                    //Sacamos del usuario su contraseña cifrada
                    foreach ($users as $u) {
                        $name = $u['username'];
                        $hashed_password = $u['password'];
                    }
                    //verificamos la contraseña introducida en el login con la cifrada con la funcion la verifica
                    if ($pass_hash == $hashed_password) {

                        //Echo "login correcto";
                        //Crea la cookie para almacenar el nombre del usuario que expira en 20 dias
                        setcookie("guardarNombre", $name, time() + 20 * 24 * 60 * 60);
                        //crea la sesion user donde almacenamos el username
                        $_SESSION['user'] = $username;
                    }
                    //si la contraseña que nos ha pasado no coincide con la de la base de datos lo redericcionamos al login
                    else {
                        header('Location: ../index.php?error=2');
                    }
                }
                //si la consulta nos devuelve más o menos lineas que no sea 1 lo redericcionamos al login
                else {
                    header('Location: ../index.php?error=2');
                }
            //si los campos estan vacios lo redericcionamos al login
            }else {
                header('Location:../index.php?error=1');
            }
        }
    //si la sesion user existe guardamos el nombre del usuario en la cookie
    }else {
        $name = htmlspecialchars($_COOKIE['guardarNombre']);
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
                    <a class="navbar-brand" href="#">
                        <img class="header__img" src="../assets/images/titulo.png" alt="">
                        <p class="header__link">VideoClub Rubio</p>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="contenedor__ul collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav d-flex me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link active" aria-current="page" href="#">INICIO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">PELICULAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">CONTACTO</a>
                            </li>
                        </ul>
                        <div class="contenedor__icon">
                            <form class="d-flex" role="search">
                                <button class="btn__icon btn" type="submit">CERRAR SESIÓN</button>
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
        <div class="container">
            <h1 class="principal__title">Bienvenido/a <?php echo ucfirst($name)?></h1>
        </div>
    </body>
    <!-- FIN BODY -->
</html>
