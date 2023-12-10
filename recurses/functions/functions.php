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

    function consultaLogin($dni) {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                $sql = "select nombre, apellidos, contraseña from jugadores where dni='$dni'";
                $select = $bd->query($sql);
                return $select;
            } catch (Exception $exc) {
                header('Location: ../../pages/log_in.php?error=1');
            }
        }
    }

    
?>

