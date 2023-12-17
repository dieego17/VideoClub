<?php

    //para incluir las funciones que haya en functions.php
    include '../lib/functions/functions.php';
    
    //iniciamos la sesion
    session_start();
    
    //nos conectamos al base de datos atraves de la funcion
    $bd = conexionBD();
    
    //obtemos el id atraves de get
    $id = htmlspecialchars($_GET['id']);
    
    //llamamos a la funcion deleteReserva y le pasamos los parametros que anteriormente hemos guardado en las variables id
    deletePelicula($id);

?>
