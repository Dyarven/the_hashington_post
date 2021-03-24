<?php
$usuario = "";
$contrasinal = "";
$usuario_err = "";
$contrasinal_err = "";
$tipo_usuario="";
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
session_start();
if (isset($_SESSION['username'])) {
sleep(1);
    if(isset($_SESSION['pax_prev'])) {
        header('location: '.$_SESSION['pax_prev']);
    } else {
        header('location: ../inicio_admin.php');
    }

    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
    }
    session_unset();
    session_destroy();
} else {
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["usuario"]))){
            $username_err = "Introduce un usuario";
        } else {
            $usuario = trim($_POST["usuario"]);
        }
        if(empty(trim($_POST["contrasinal"]))) {
            $contrasinal_err = "Introduce o teu contrasinal.";
        } else {
            $contrasinal = trim(htmlspecialchars($_POST["contrasinal"]));
        }
        $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
        if ($db) {
            $consulta = "SELECT * FROM usuarios where nome_usuario = '$usuario'";
            $res_consulta = mysqli_query($db, $consulta);
            $consulta_contar = "SELECT count(*) FROM usuarios where nome_usuario = '$usuario'";
            $res_consulta_contar = mysqli_query($db, $consulta_contar);
            $num_resultados = mysqli_fetch_row($res_consulta_contar);
            if ($num_resultados[0] > 0) {
                if ($res_consulta) {
                    $usuarios = mysqli_fetch_assoc($res_consulta);
                    if ($usuario == $usuarios['nome_usuario']) {
                        if (password_verify($contrasinal, $usuarios['contrasinal'])) {
                            $tipo_usuario = $usuarios['tipo_usuario'];
                            $nome_completo = $usuarios['nome_completo'];
                            $id_usuario = $usuarios['id_usuario'];
                            #inicia sesión
                            session_start();
                            #almacena variables de sesión
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $usuario;
                            $_SESSION["user_type"] = $tipo_usuario;
                            $_SESSION["user_fullname"] = $nome_completo;
                            $_SESSION["user_id"] = $id_usuario;
                            #redirixete á páxina anterior excepto nas de edición ou inserción
                            if(isset($_SESSION['pax_prev'])) {
                                header('location: '.$_SESSION['pax_prev']);
                            } else {
                                header('location: ../inicio_admin.php');
                            }
                        } else {
                            $contrasinal_err = "Contrasinal incorrecto.";
                        }
                    } else {
                        $usuario_err = "O nome do usuario non é correcto ou non existe";
                    }
                } else {
                    echo "<h3>Uuups, algo saíu moi mal se estás vendo esto</h3>";
                }
            } else {
                $usuario_err = "O nome do usuario non é correcto ou non existe";
            }
        mysqli_close($db);
        }
    }
}

?>
