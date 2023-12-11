<?php

    /**
     * Funcion para conectarnos a la base de datos
     * 
     * @return \PDO
     */
    function conexionBD() {
        $cadena_conexion = 'mysql:dbname=videoclub;host=127.0.0.1';
        $usuario = 'root';
        $clave = '';

        try {
            //Se crea la conexión con la base de datos
            $bd = new PDO($cadena_conexion, $usuario, $clave);
            return $bd;
        } catch (Exception $e) {
            return null;
        }
    }

    function consultaLogin($username, $password) {
        
    }

    
    
?>

