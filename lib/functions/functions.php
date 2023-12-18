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
            header('Location: ../pages/page404.php');
            exit();
        }
    }

    /**
     * Función para comprobar el usuario de la base de datos
     * 
     * @param type $username
     * @return type
     */
    function consultaLogin($username) {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                $sql = "select id, username, password, rol from usuarios where username = ?";
                $select = $bd->prepare($sql);
                $select -> execute([$username]);
                
                return $select;
            } catch (Exception $exc) {
                header('Location: ../../pages/page404.php');
                exit();
            }
        }
    }


    

    /**
     * Función para mostrar cada pelicula de la base de datos
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
                header('Location: ../../pages/page404.php');
                exit(); 
            }
        } else {
            header("Location: ../index.php");
            exit(); 
        }
        return $arrayPeliculas;
    }

    
    /**
     * Función para mostrar los actores de la base de datos
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
                $prepares = $bd->prepare("SELECT * FROM actores where id IN (SELECT idActor FROM actuan WHERE idPelicula = ?);");
                $prepares->execute(array($idPelicula));

                // Obtener los resultados como un array asociativo
                $resultados = $prepares->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $resultado) {
                    $actor = new Actor($resultado["id"], $resultado["nombre"], $resultado["apellidos"], $resultado["fotografia"]);
                    array_push($arrayActores, $actor);
                }
            } catch (Exception $exc) {
                header('Location: ../../pages/page404.php');
                exit();
            }
        } else {
            header("Location:../index.php");
            exit();
        }

        return $arrayActores;
    }

    /**
     * Función para mostrar los actores en paro
     * 
     * @return array
     */
    function consultaActoresParo() {
        $bd = conexionBD();
        $arrayActoresParo = array();

        if ($bd != null) {
            try {
                $prepares = $bd->prepare("SELECT * FROM actores where id NOT IN (SELECT idActor FROM actuan);");
                $prepares->execute();

                // Obtener los resultados como un array asociativo
                $resultados = $prepares->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $resultado) {
                    $actor = new Actor($resultado["id"], $resultado["nombre"], $resultado["apellidos"], $resultado["fotografia"]);
                    array_push($arrayActoresParo, $actor);
                }
            } catch (Exception $exc) {
                header('Location: ../../pages/page404.php');
                exit();
            }
        } else {
            header("Location:../index.php");
            exit();
        }

        return $arrayActoresParo;
    }


    /**
     * Función para borrar una película de la base de datos
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
                header('Location: ../../pages/page404.php');
                exit(); 
            }
        }else {
            header("Location:../pages/cerrarSesion.php");
            exit();
        }
    }
    
    /**
     * Función para modificar una película ya existente en la base de datos
     * 
     * @param type $id
     * @param type $nuevoTitulo
     * @param type $nuevoGenero
     * @param type $nuevoPais
     * @param type $nuevoAnyo
     * @param type $nuevoCartel
     */
    function modificarPelicula($id, $nuevoTitulo, $nuevoGenero, $nuevoPais, $nuevoAnyo, $nuevoCartel) {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                
                //Modificamos la pelicula seleccionada 
                $sqlPelicula = "UPDATE peliculas SET titulo = ?, genero = ?, pais = ?, anyo = ?, cartel = ? WHERE id = ?";
                $modificarPeli = $bd->prepare($sqlPelicula);
                $modificarPeli -> execute([$nuevoTitulo, $nuevoGenero, $nuevoPais, $nuevoAnyo, $nuevoCartel, $id]);
                

                header('Location: ../pages/inicioAdmin.php');
                exit();

            } catch (Exception $exc) {
                header('Location: ../../pages/page404.php');
                exit(); 
            }
        }else {
            header("Location:../pages/cerrarSesion.php");
            exit();
        }
    }
    
    
    /**
     * Función para añadir nuevas peliculas tomando de referencia el id mas alto y añadiendole 1 más
     * 
     * @param type $nuevoTitulo
     * @param type $nuevoGenero
     * @param type $nuevoPais
     * @param type $nuevoAnyo
     * @param type $nuevoCartel
     */
    function crearPelicula($nuevoTitulo, $nuevoGenero, $nuevoPais, $nuevoAnyo, $nuevoCartel) {
        $bd = conexionBD();
        if ($bd != null) {
            try {
                
                // Obtener el máximo ID de la tabla películas
                $sqlMaxID = "SELECT MAX(id) 'idMax' FROM peliculas";
                $MaxID = $bd->prepare($sqlMaxID);
                $MaxID->execute();
                $maxIDResult = $MaxID->fetch(PDO::FETCH_ASSOC);
                
                $nuevoID = $maxIDResult['idMax'] + 1;
                
                //Insertamos la nueva pelicula 
                $sqlPelicula = "INSERT INTO peliculas (id, titulo, genero, pais, anyo, cartel) VALUES (?, ?, ?, ?, ?, ?)";
                $modificarPeli = $bd->prepare($sqlPelicula);
                $modificarPeli -> execute([$nuevoID, $nuevoTitulo, $nuevoGenero, $nuevoPais, $nuevoAnyo, $nuevoCartel]);
                

                header('Location: ../pages/inicioAdmin.php');
                exit();

            } catch (Exception $exc) {
                header('Location: ../../pages/page404.php');
                exit(); 
            }
        }else {
            header("Location:../pages/cerrarSesion.php");
            exit();
        }
    }
?>


