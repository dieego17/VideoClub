<?php
    
    //INICIAMOS UNA SESIÓN
    session_start();
    //para borrar todas las sesiones que existan.
    $_SESSION = array();
    session_destroy();
    //para que te lleve al login
    header('Location: ../index.php');
    setcookie("guardarNombre", "",time()-1);


?>
