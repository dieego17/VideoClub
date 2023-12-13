<?php
    //para incluir las funciones que haya en functions.php
    include '../lib/functions/functions.php';
    
    //variable para guardar la fecha actual y saber cuando fue la ultima visita del usuario
    $fecha = date("d/m/Y | H:i:s");
    
    $name;
    $hashed_password;
    
    //inicio de la sesion
    session_start();
    //comprobamos que no existe la sesion user
    if (!isset($_SESSION['user'])) {
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);
            //comprobamos que los campos username y password no esten vacios
            if ($username != "" && $password != "") {
                //ciframos la contraseña
                $pass_hash = hash("sha256", $password);
                //nos conectamos a la base de datos
                $bd = conexionBD();
                //guardamos la sentencia sql para luego utilizarla
                $users = consultaLogin($username);
                if (gettype($users) == null) {
                    header('Location: ../index.php?error=2');
                    exit();
                } else {
                    $user=$users->rowCount();
                }

                //Comprueba que la consulta nos ha devuelto solo una 1 fila que es la que indica que no hay ningun usuario repetido.
                if ($user == 1) {
                    //Sacamos del usuario su contraseña cifrada
                    foreach ($users as $u) {
                        $name = $u['username'];
                        $hashed_password = $u['password'];
                        $rol = $u['rol'];
                    }
                    //verificamos la contraseña introducida en el login con la cifrada con la funcion la verifica
                    if ($pass_hash == $hashed_password) {

                        //Crea la cookie para almacenar el nombre del usuario que expira en 20 dias
                        setcookie("guardarNombre", $name, time() + 20 * 24 * 60 * 60);
                        //Creamos la cookie par almacenar la fecha de la ultima visita del usuario que expira en 20 dias
                        setcookie("fecha", $fecha, time() + 20 * 24 * 60 * 60);
                        
                        //crea la sesion user donde almacenamos el username
                        $_SESSION['user'] = $username;
                        // almacena el rol del usuario en la sesión
                        $_SESSION['rol'] = $rol;
                        
                        if($rol === 1){
                            header('Location: ../pages/inicioAdmin.php');
                        }else if($rol === 0){
                            header('Location: ../pages/inicioUser.php');
                        }
                    }
                    //si la contraseña que nos ha pasado no coincide con la de la base de datos lo redericcionamos al login
                    else {
                        header('Location: ../index.php?error=2');
                        exit();
                    }
                }
                //si la consulta nos devuelve más o menos lineas que no sea 1 lo redericcionamos al login
                else {
                    header('Location: ../index.php?error=2');
                    exit();
                }
            //si los campos estan vacios lo redericcionamos al login
            }else {
                header('Location:../index.php?error=1');
                exit();
            }
        }else {
            header('Location:../index.php?error=2');
            exit();
        }
    //si la sesion user existe guardamos el nombre del usuario en la cookie
    }else {
        $name = htmlspecialchars($_COOKIE['guardarNombre']);
    }
?>