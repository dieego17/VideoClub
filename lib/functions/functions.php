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
     * Función para sacar cada pelicula
     * 
     * @return array
     */
    function consultaPeliculas() {
        $bd = conexionBD();

        $arrayPeliculas = array();

        if ($bd != null) {
            try {
                $prepares = $bd->prepare("SELECT p.id, p.titulo, p.anyo, p.genero, p.pais, p.cartel, a.nombre, a.id, a.apellidos, a.fotografia
                FROM peliculas p JOIN actuan c ON p.id = c.idPelicula 
                JOIN actores a ON c.idActor = a.id;");
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
     * Función para sacar los actores 
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
                $prepares = $bd->prepare("SELECT * FROM actores WHERE id = ?");
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


