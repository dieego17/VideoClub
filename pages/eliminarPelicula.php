<?php

    //Incluir las funciones que haya en functions.php
    include '../lib/functions/functions.php';
    
    //Iniciamos la sesion
    session_start();
    
    //Nos conectamos a la base de datos a traves de la función
    $bd = conexionBD();
    
    //Obtemos el id atraves de get
    $id = htmlspecialchars($_GET['id']);
    
    //Llamamos a la funcion deletePelicula y le pasamos el parametro del Id de la Película
    deletePelicula($id);

?>
