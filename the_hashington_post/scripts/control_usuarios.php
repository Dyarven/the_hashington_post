<?php
include 'funcions_control.php';
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
$accion= $exito = $exito_ins = "";
$usuarioErr = $usuarioErr2 = $nome_completoErr = $emailErr = $contrasinalErr = $nome_completoErr2 = $emailErr2 = $usuarioErr3 = $emailErr3 = "";
$nome_usuario = $nome_completo = $email = $contrasinal = $exito_del = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
    }
    switch($accion) {
        case 'engadir':
        if (!empty($_POST["nome_usuario"])) {
            $nome_usuario = limpar_datos($_POST["nome_usuario"]);
            $nome_usuario = preg_replace('/[^a-zA-Z0-9]/', '', $nome_usuario);;
            if (!empty($_POST["nome_completo"])) {
                $nome_completo = $_POST['nome_completo'];
                $nome_completo = preg_replace('/[^a-zA-Z\s]/', '', $nome_completo);;
                if (!empty($_POST["email"])) {
                    $email = limpar_datos($_POST["email"]);
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if (!empty($_POST["contrasinal"])) {
                            $contrasinal = $_POST['contrasinal'];
                            $contrasinal = password_hash($contrasinal, PASSWORD_BCRYPT);
                            $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                            if ($db) {
                                if (!empty($_POST['contrasinal'])) {
                                    $consulta = "INSERT INTO usuarios (nome_completo, nome_usuario, tipo_usuario, email, contrasinal) values (?, ?, 'editor', ?, ?)";
                                    $consulta_preparada = mysqli_prepare($db, $consulta);
                                    mysqli_stmt_bind_param($consulta_preparada, 'ssss', $nome_completo, $nome_usuario, $email, $contrasinal);
                                    $exec_consulta = mysqli_stmt_execute($consulta_preparada);
                                    if ($exec_consulta) {
                                        $exito_ins = "<h4>Usuario engadido correctamente</h4>";
                                    } else {
                                        $exito_ins = "<h4>Erro ao engadir</h4>";
                                    }
                            }
                        } mysqli_close($db);
                    } else {
                            $contrasinalErr = "Introduce un contrasinal temporal";
                    }
                }
            } else {
                    $emailErr2 = "Introduce un email";
                }
            } else {
                $nome_completoErr2 = "Introduce o nome completo";
            }
        } else {
            $usuario_err2 = "Introduce un nome de usuario";
        }
        break;
        case 'modificar':
            if (!empty($_POST["contrasinal"])) {
                $contrasinal = $_POST['contrasinal'];
                $contrasinal = password_hash($contrasinal, PASSWORD_BCRYPT);
            }
            $id_usuario = $_POST['id_usuario'];
            if (!empty($_POST["nome_usuario"])) {
                $nome_usuario = limpar_datos($_POST["nome_usuario"]);
                $nome_usuario = preg_replace('/[^a-zA-Z0-9]/', '', $nome_usuario);;
                if (!empty($_POST["nome_completo"])) {
                    $nome_completo = $_POST['nome_completo'];
                    $nome_completo = preg_replace('/[^a-zA-Z\s]/', '', $nome_completo);;
                    if (!empty($_POST["email"])) {
                        $email = limpar_datos($_POST["email"]);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                            if ($db) {
                                if (!empty($_POST['contrasinal'])) {
                                    $consulta = "UPDATE usuarios SET nome_usuario=?, nome_completo=?, email=?, contrasinal=?
                                    where id_usuario=$id_usuario";
                                    $consulta_preparada = mysqli_prepare($db, $consulta);
                                    mysqli_stmt_bind_param($consulta_preparada, 'ssss', $nome_usuario, $nome_completo, $email, $contrasinal);
                                } else {
                                    $consulta = "UPDATE usuarios SET nome_usuario=?, nome_completo=?, email=?
                                    where id_usuario=$id_usuario";
                                    $consulta_preparada = mysqli_prepare($db, $consulta);
                                    mysqli_stmt_bind_param($consulta_preparada, 'sss', $nome_usuario, $nome_completo, $email);
                                }
                                $exec_consulta = mysqli_stmt_execute($consulta_preparada);
                                if ($exec_consulta) {
                                    $exito = "<h4>Usuario modificado correctamente</h4>";
                                } else {
                                    $exito = "<h4>Erro ao modificar</h4>";
                                }
                            } mysqli_close($db);
                        }
                    } else {
                        $emailErr = "Introduce un email";
                    }
                } else {
                    $nome_completoErr = "Introduce o nome completo";
                }
            } else {
                $usuario_err = "Introduce un nome de usuario";
            }
        break;
        case 'eliminar':
            $id_usuario = $_POST['id_usuario'];
            $email = $_POST["email"];
            $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
            if ($db) {
                $consulta = "DELETE from usuarios where id_usuario='$id_usuario'";
                $exec_consulta = mysqli_query($db, $consulta);
                if ($exec_consulta) {
                    $exito_del = "<h4>Usuario eliminado correctamente</h4>";
                } else {
                    $exito_del = "<h4>Erro ao eliminar, o usuario aínda é un editor activo.</h4>";
                }
            } mysqli_close($db);
        break;
    }
}

?>
