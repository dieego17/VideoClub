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

    /**
     * 
     * @param type $username
     * @return type
     */
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
     * 
     * @return type
     */
    function consultaPeliculas() {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                $prepare = $bd->prepare("SELECT p.id, p.anyo, p.cartel, p.genero, p.pais, p.titulo, "
                        . "a.id, a.nombre, a.apellidos, a.fotografia "
                        . "FROM peliculas p "
                        . "JOIN actuan c ON p.id = c.idPelicula "
                        . "JOIN actores a ON c.idPelicula = a.id;");
                $prepare->execute(array());
                //$select = $bd->query($sql);
                return $prepare;
            } catch (Exception $exc) {

            }
        } else {
            header("Location:../index.php");
        }
    }
    

    /**
     * 
     * @param type $titulo
     * @param type $genero
     * @param type $pais
     * @param type $anyo
     * @param type $cartel
     */
    function deletePelicula($titulo, $genero, $pais, $anyo, $cartel) {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                $sql = "delete from peliculas where titulo='$titulo' and genero='$genero' and pais='$pais' and anyo='$anyo' and cartel='$cartel'";
                $delete = $bd->query($sql);
                if ($delete) {
                    header('Location: ../pages/inicioAdmin.php');
                }
            } catch (Exception $exc) {

            }
        }else {
            header("Location:../pages/cerrarSesion.php");
        }
    }
?>


