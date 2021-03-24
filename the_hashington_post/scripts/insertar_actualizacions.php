<?php
include 'funcions_control.php';
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
$titulo_err = $contido_err = $num_ver_err = "";
$contido = $num_version = $titulo = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    //editando ou eliminando
    if (isset($_POST['id_artigo'])) {
        $id_artigo = $_POST['id_artigo'];
        if (empty($_POST['titulo'])) {
            echo "<p class='h4err'>Introduce un título<p>";
        } else {
            $titulo = $_POST['titulo'];
            $titulo = htmlentities(stripslashes(strip_tags($titulo)));
            if (empty($_POST['contido'])) {
                echo "<p class='h4err'>Describe a actualización<p>";
            } else {
                $contido = $_POST['contido'];
                $contido = htmlentities(stripslashes(strip_tags($contido)));
                $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                if ($db) {
                    if (isset($_POST['accion'])) {
                        if ($_POST['accion'] == 'Gardar') {
                            $consulta = "UPDATE actualizacions_software SET titulo=?, contido=?  where id_artigo =?";
                            $consulta_preparada = mysqli_prepare($db, $consulta);
                            mysqli_stmt_bind_param($consulta_preparada, 'sbi', $titulo, $contido, $id_artigo);
                            mysqli_stmt_send_long_data($consulta_preparada, 1, $contido);
                            $exec_consulta = mysqli_stmt_execute($consulta_preparada);
                            if ($exec_consulta) {
                                echo "<p class='header_updates'>Actualización insertada con éxito</p>";
                                $contido = $num_version = $titulo = "";
                            }
                        } elseif ($_POST['accion'] == 'Eliminar') {
                            $consulta = "DELETE FROM actualizacions_software where id_artigo ='".$id_artigo."'";
                            $exec_consulta = mysqli_query($db, $consulta);
                            if ($exec_consulta) {
                                echo "<p class='header_updates'>Actualización eliminada con éxito</p>";
                                $contido = $num_version = $titulo = "";
                            }
                        }
                    }
                } mysqli_close($db);
            }
        }
    } else {
        //insertando nova actualización
        $id_artigo = "";
        if (empty($_POST['titulo'])) {
            $titulo_err = "Introduce un título";
        } else {
            $titulo = $_POST['titulo'];
            $titulo = htmlentities(stripslashes(strip_tags($titulo)));
            if (empty($_POST['contido'])) {
                $contido_err = "Describe a actualización";
            } else {
                $contido = $_POST['contido'];
                $contido = htmlentities(stripslashes(strip_tags($contido)));
                if (empty($_POST['num_version'])) {
                    $num_ver_err = "Introduce o numero de versión";
                } else {
                    $num_version = $_POST['num_version'];
                    if (preg_match("/^[0-9.][0-9.]+$/i", $num_version)) {
                        $so = $_POST['sistema_operativo'];
                        $id_usuario = $_SESSION['user_id'];
                        $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                        if ($db) {
                            if (isset($_POST['accion'])) {
                                if ($_POST['accion'] == 'Gardar') {

                                    $consulta = "INSERT INTO actualizacions_software (num_version, sistema_operativo, titulo, contido, id_usuario)
                                    values (?, ?, ?, ?, ?)";
                                    $consulta_preparada = mysqli_prepare($db, $consulta);
                                    mysqli_stmt_bind_param($consulta_preparada, 'sssbi', $num_version, $so, $titulo, $contido, $id_usuario);
                                    mysqli_stmt_send_long_data($consulta_preparada, 3, $contido);
                                    $exec_consulta = mysqli_stmt_execute($consulta_preparada);
                                    if ($exec_consulta) {
                                        echo "<p class='header_updates'>Actualización insertada con éxito</p>";
                                    }
                                }
                            }
                        } mysqli_close($db);
                    } else {
                        $num_ver_err = "inserta un numero de versión válido";
                    }
                }
            }
        }
    }
}
