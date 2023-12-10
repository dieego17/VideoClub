<?php

    //para incluir las funciones que haya en functions.php
    include '../functions/functions.php';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $usuario = htmlspecialchars($_POST["usuario"]);
        $contraseña = htmlspecialchars($_POST["contraseña"]);

        //ciframos la contraseña
        $pass = hash("sha256", $contraseña);
        //comprobamos que todos los campos estan completados
        if ($usuario != "" && $pass != "") {
            //echo "campos completados";
            $bd = conexionBD();
            if ($bd != null) {
                echo "buenas";
            } else {
                header('Location:../../index.php?error=2');
            }
        } else {
            header('Location:../../index.php?error=1'); 
        }
    }
    
?>
