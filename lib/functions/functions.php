<?php

    /**
     * Funcion para conectarnos a la base de datos
     * 
     * @return \PDO
     */
    function conexionBD() {
        $cadena_conexion = 'mysql:dbname=videoclub2;host=127.0.0.1';
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
     * Función para comprobar el usuario
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
                exit();
            }
        }
    }


    

    /**
     * Función para mostrar cada pelicula
     * 
     * @return array
     */
    function consultaPeliculas() {
        $bd = conexionBD();
        $arrayPeliculas = array();

        if ($bd != null) {
            try {
                $prepares = $bd->prepare("SELECT * FROM peliculas;");
                $prepares->execute();

                // Obtener los resultados como un array asociativo
                $resultados = $prepares->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $resultado) {
                    $pelicula = new Pelicula($resultado["id"], $resultado["titulo"], $resultado["genero"], $resultado["pais"], $resultado["anyo"], $resultado["cartel"]);
                    array_push($arrayPeliculas, $pelicula);
                }
            } catch (Exception $exc) {
                // Manejo de errores
            }
        } else {
            header("Location: ../index.php");
            exit(); 
        }
        return $arrayPeliculas;
    }

    
    /**
     * Función para mostrar los actores 
     * 
     * @param type $pelicula
     * @return array
     */
    function consultaActores($pelicula) {
        $bd = conexionBD();
        $arrayActores = array();

        if ($bd != null) {
            try {
                $idPelicula = $pelicula->getId();
                $prepares = $bd->prepare("SELECT * FROM actores where id IN (SELECT idActor FROM actuan WHERE idPelicula=?);");
                $prepares->execute(array($idPelicula));

                // Obtener los resultados como un array asociativo
                $resultados = $prepares->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $resultado) {
                    $actor = new Actor($resultado["id"], $resultado["nombre"], $resultado["apellidos"], $resultado["fotografia"]);
                    array_push($arrayActores, $actor);
                }
            } catch (Exception $exc) {
                // Manejo de errores
            }
        } else {
            header("Location:../index.php");
            exit();
        }

        return $arrayActores;
    }



    /**
     * Función para borrar una película
     * 
     * @param type $id
     */
    function deletePelicula($id) {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                
                //Borramos en la tabla actuan el idPelicula 
                $sqlActuan = "DELETE FROM actuan WHERE idPelicula = ?";
                $deleteActuan = $bd->prepare($sqlActuan);
                $deleteActuan -> execute([$id]);
                
                //Borramos la pelicula seleccionada de la tabla peliculas
                $sqlPelicula = "DELETE FROM peliculas WHERE id = ?";
                $deletePelicula = $bd->prepare($sqlPelicula);
                $deletePelicula -> execute([$id]);
                

                header('Location: ../pages/inicioAdmin.php');
                exit();

            } catch (Exception $exc) {
                header("Location:../pages/cerrarSesion.php");
                exit();
            }
        }else {
            header("Location:../pages/cerrarSesion.php");
            exit();
        }
    }
    
    function modificarPelicula($id, $nuevoTitulo, $nuevoGenero, $nuevoPais, $nuevoAnyo, $nuevoCartel) {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                
                //Borramos la pelicula seleccionada de la tabla peliculas
                $sqlPelicula = "UPDATE peliculas SET titulo = ?, genero = ?, pais = ?, anyo = ?, cartel = ? WHERE id = ?";
                $modificarPeli = $bd->prepare($sqlPelicula);
                $modificarPeli -> execute([$nuevoTitulo, $nuevoGenero, $nuevoPais, $nuevoAnyo, $nuevoCartel, $id]);
                

                header('Location: ../pages/inicioAdmin.php');
                exit();

            } catch (Exception $exc) {
                header("Location:../pages/cerrarSesion.php");
                exit();
            }
        }else {
            header("Location:../pages/cerrarSesion.php");
            exit();
        }
    }
?>


