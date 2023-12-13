<?php

    //para incluir las funciones que haya en functions.php
    include '../lib/functions/functions.php';
    //iniciamos la sesion
    session_start();
    //nos conectamos al base de datos atraves de la funcion
    $bd = conexionBD();
    //echo $_SESSION['user'];
    //obtemos la fecha atraves de get
    $titulo=htmlspecialchars($_GET['titulo']);
    //obtemos la hora atraves de get
    $genero=htmlspecialchars($_GET['genero']);
    //obtenemos la pista a traves de get
    $pais = htmlspecialchars($_GET['pais']);
    //obtenemos la pista a traves de get
    $anyo = htmlspecialchars($_GET['anyo']);
    //obtenemos la pista a traves de get
    $cartel = htmlspecialchars($_GET['cartel']);
    
    //llamamos a la funcion deleteReserva y le pasamos los parametros que anteriormente hemos guardado en las variables dni, fecha, hora,
    deleteReserva($titulo, $genero, $pais, $anyo, $cartel);

?>
