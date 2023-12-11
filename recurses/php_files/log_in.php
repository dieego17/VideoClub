<?php

    //para incluir las funciones que haya en functions.php
    include '../functions/functions.php';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = htmlspecialchars($_POST["usuario"]);
        $password = htmlspecialchars($_POST["contraseña"]);

        //ciframos la contraseña
        //$pass = hash("sha256", $password);
        //comprobamos que todos los campos estan completados
        if ($username != "" && $password != "") {
            $bd = conexionBD();
            if($bd != null){
                consultaLogin($username, $password);
            }else{
                 header('Location:../../index.php?error=2');
            }
        } else {
            header('Location:../../index.php?error=1'); 
        }
    }
    
?>
