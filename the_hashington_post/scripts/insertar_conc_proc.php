<?php
$contido_err = $definicion_preview_err = $titulo_err = "";
$contido = $titulo = $definicion_preview = "";
$datos_conexion = parse_ini_file('./ini/datos_conexion.ini',true);
include 'funcions_control.php';

if (isset($_POST['selcp'])) {
    $selcp = $_POST['selcp'];

    if (!empty($_POST['titulo'])) {
        $titulo = $_POST['titulo'];

        if (!empty($_POST['definicion_preview'])) {
            $definicion_preview = $_POST['definicion_preview'];

            if (!empty($_POST['contido'])) {
                $contido = $_POST['contido'];

                if (isset($_POST['id_artigo'])) {
                    $id_artigo = $_POST['id_artigo'];
                } else {
                    $id_artigo = "";
                }

                if (isset($_FILES['imaxe_preview'])) {
                    if ($_FILES['imaxe_preview']['error'] === UPLOAD_ERR_OK) {
                        $ruta_temporal= $_FILES['imaxe_preview']['tmp_name'];
                        $nome_arquivo= $_FILES['imaxe_preview']['name'];
                        $extension= explode('.',($nome_arquivo));
                        $extension= strtolower(end($extension));
                        $extensions_validas = array ( 'jpg', 'gif', 'png', 'jpeg');
                        //revisar extensión
                        if (in_array($extension, $extensions_validas)) {
                            $directorio_subida = "../imaxes/concep_proced/";
                            $ruta_imaxe= $directorio_subida."cp".$id_artigo."preview.".$extension;
                            $imaxe_preview = $ruta_imaxe;
                            move_uploaded_file($ruta_temporal, $ruta_imaxe ) ;
                        } else {
                            echo "
                                <script>
                                    alert('A extensión da imaxe debe ser jpg, gif, png ou jpeg');
                                    window.history.go(-1);
                                </script>";
                            exit;
                        }
                    } elseif ($_FILES['imaxe_preview']['error'] === 4) {
                        $imaxe_preview = "";
                    } else {
                        echo "algo fallou ao subir a imaxe";
                    }
                 } else {
                    $imaxe_preview = "";
                 }

                    if (isset($_FILES['imaxe'])) {
                        if ($_FILES['imaxe']['error'] === UPLOAD_ERR_OK) {
                            $ruta_temporal = $_FILES['imaxe']['tmp_name'];
                            $nome_arquivo = $_FILES['imaxe']['name'];
                            $extension = explode('.',($nome_arquivo));
                            $extension = strtolower(end($extension));
                            $extensions_validas = array ( 'jpg', 'gif', 'png', 'jpeg');
                            if (in_array($extension, $extensions_validas)) {
                                $directorio_subida = "../imaxes/concep_proced/";
                                $ruta_imaxe= $directorio_subida."cp".$id_artigo.".".$extension;
                                $imaxe = $ruta_imaxe;
                                move_uploaded_file($ruta_temporal, $ruta_imaxe ) ;
                            } else {
                                echo "
                                    <script>
                                        alert('A extensión da imaxe debe ser jpg, gif, png ou jpeg');
                                        window.history.go(-1);
                                    </script>";
                                exit;
                            }
                        } elseif ($_FILES['imaxe']['error'] === 4) {
                            $imaxe = "";
                        } else {
                            echo "algo fallou ao subir a imaxe";
                        }
                    } else {
                        $imaxe = "";
                    }
            ////////////////limpar datos////////////////
                $titulo = limpar_datos($titulo);
                $definicion_preview = htmlentities(stripslashes(strip_tags($definicion_preview)));
                $contido = htmlentities(stripslashes(strip_tags($contido)));
                $id_usuario = $_SESSION['user_id'];

            /////////////////inserción na base de datos///////////////////
                $datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
                $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                if ($_POST['accion'] == 'Gardar') {
                    if ($db) {
                        if (!empty($_POST['id_artigo'])) {
                            if (($imaxe == "") AND ($imaxe_preview == "")) {
                                $consulta = "UPDATE concep_proced SET titulo=?, definicion_preview=?, contido=?, categoría=?
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta_preparada = mysqli_prepare($db, $consulta);
                                mysqli_stmt_bind_param($consulta_preparada, 'sbbs', $titulo, $definicion_preview, $contido, $selcp);
                                mysqli_stmt_send_long_data($consulta_preparada,1,$definicion_preview);
                                mysqli_stmt_send_long_data($consulta_preparada,2,$contido);
                            } elseif ($imaxe_preview == "") {
                                $consulta = "UPDATE concep_proced SET titulo=?, definicion_preview=?, contido=?, imaxe=?, categoría=?
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta_preparada = mysqli_prepare($db, $consulta);
                                mysqli_stmt_bind_param($consulta_preparada, 'sbbss', $titulo, $definicion_preview, $contido, $imaxe, $selcp);
                                mysqli_stmt_send_long_data($consulta_preparada,1,$definicion_preview);
                                mysqli_stmt_send_long_data($consulta_preparada,2,$contido);
                            } elseif ($imaxe == "") {
                                $consulta = "UPDATE concep_proced SET titulo=?, definicion_preview=?, contido=?, imaxe_preview=?, categoría=?
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta_preparada = mysqli_prepare($db, $consulta);
                                mysqli_stmt_bind_param($consulta_preparada, 'sbbss', $titulo, $definicion_preview, $contido, $imaxe_preview, $selcp);
                                mysqli_stmt_send_long_data($consulta_preparada,1,$definicion_preview);
                                mysqli_stmt_send_long_data($consulta_preparada,2,$contido);
                            } else {
                                $consulta = "UPDATE concep_proced SET titulo=?, definicion_preview=?, contido=?, imaxe_preview=?, imaxe=?, categoría=?
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta_preparada = mysqli_prepare($db, $consulta);
                                mysqli_stmt_bind_param($consulta_preparada, 'sbbsss', $titulo, $definicion_preview, $contido, $imaxe_preview, $imaxe, $selcp);
                                mysqli_stmt_send_long_data($consulta_preparada,1,$definicion_preview);
                                mysqli_stmt_send_long_data($consulta_preparada,2,$contido);
                            }
                        } else {
                            $consulta = "INSERT into concep_proced (titulo, definicion_preview, imaxe_preview, contido, imaxe, id_usuario, categoría) values ('$titulo', '$definicion_preview', '$imaxe_preview', '$contido', '$imaxe', '$id_usuario', '$selcp')";
                            $consulta = "INSERT into concep_proced (titulo, definicion_preview, imaxe_preview, contido, imaxe, id_usuario, categoría) values (?, ?, ?, ?, ?, ?, ?)";
                            $consulta_preparada = mysqli_prepare($db, $consulta);
                            mysqli_stmt_bind_param($consulta_preparada, 'sbsbsis', $titulo, $definicion_preview, $imaxe_preview, $contido, $imaxe, $id_usuario, $selcp);
                            mysqli_stmt_send_long_data($consulta_preparada,1,$definicion_preview);
                            mysqli_stmt_send_long_data($consulta_preparada,3,$contido);
                        }
                        $exec_consulta = mysqli_stmt_execute($consulta_preparada);
                        if ($exec_consulta) {
                            echo "<p class='header_updates'>Datos insertados con éxito.</p>";
                                $contido = $titulo = $definicion_preview = "";
                        } else {
                            echo "<p class='header_updates'>Erro ao inserir datos.</p>";
                        }
                    } mysqli_close($db);
                } elseif ($_POST['accion'] == 'Eliminar') {
                    $consulta_borrar = "DELETE FROM concep_proced where id_artigo=$id_artigo";
                    $borrar = mysqli_query($db, $consulta_borrar);
                    if ($borrar) {
                        echo "<p class='header_updates'>Artigo eliminado.</p>";
                            $contido = $titulo = $definicion_preview = "";
                    } else {
                        echo "<p class='header_updates'>Houbo un problema ao eliminar os datos.</p>";
                    }
                }
            } else {
                echo "debes introducir contido";
            }
        } else {
            echo "debes introducir unha definición";
        }
    } else {
        echo "debe haber un título";
    }
}

?>
