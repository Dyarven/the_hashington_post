<?php
session_start();
$noteq = $exito = $iguais = $err = "";
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI'];
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
if (isset($_SESSION['username'])) {
    if (isset($_POST['enviar'])) {
        if (!empty(trim($_POST['contrasinal_vello']))) {
            $contrasinal_vello = trim(htmlspecialchars($_POST["contrasinal_vello"]));
            if (!empty($_POST['contrasinal_novo'])) {
                $contrasinal_novo = trim(htmlspecialchars($_POST["contrasinal_novo"]));
                if (!empty($_POST['repetir_contrasinal'])) {
                    $repetir_contrasinal = trim(htmlspecialchars($_POST["repetir_contrasinal"]));
                    $usuario = $_SESSION['username'];
                    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                    if ($db) {
                        $consulta = "SELECT * FROM usuarios where nome_usuario = '$usuario'";
                        $res_consulta = mysqli_query($db, $consulta);
                        $usuarios = mysqli_fetch_assoc($res_consulta);
                        if (password_verify($contrasinal_vello, $usuarios['contrasinal'])) {
                            if ($contrasinal_novo == $repetir_contrasinal) {
                                if ($contrasinal_novo != $contrasinal_vello) {
                                    $contrasinal_novo = password_hash($contrasinal_novo, PASSWORD_BCRYPT);
                                    $cons_chpasswd = "UPDATE usuarios set contrasinal='$contrasinal_novo' where nome_usuario='$usuario'";
                                    $exec_chpasswd = mysqli_query($db, $cons_chpasswd);
                                    $exito =  "contrasinal modificado correctamente";
                                } else {
                                    $iguais = "o contrasinal novo non pode ser igual ao anterior";
                                }
                            } else {
                                $noteq= "os contrasinais non coinciden";
                            }
                        } else {
                            $err = "O contrasinal é incorrecto";
                        }
                    }
                }
            }
        }
    }
}
?>
