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

    function consultaLogin($username) {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                $sql = "select id, username, password, rol from usuarios where username='$username'";
                $select = $bd->query($sql);
                return $select;
            } catch (Exception $exc) {
                header('Location: ../../index.php?error=2');
            }
        }
    }
    
    /**
     * Funcion que nos devuelve una sentencia sql, en este caso un select
     * 
     * @param string $dni dni del usuario que queremos ver de la base de datos
     * @return string nos devuelve la sentencia sql
     */
    function consultaReservas() {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                $prepare = $bd->prepare("select id, titulo, genero, pais, anyo, cartel from peliculas");
                $prepare->execute(array());
                //$select = $bd->query($sql);
                return $prepare;
            } catch (Exception $exc) {

            }
        } else {
            header("Location:../index.php");
        }
    }
    

?>


