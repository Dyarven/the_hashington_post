<?php
$tipo_err = $resumo_err = $contido_err = $titulo_err = "";
$contido = $titulo = $resumo = $contido2 = $id_tipo_noticia = "";
$datos_conexion = parse_ini_file('./ini/datos_conexion.ini',true);
include 'funcions_control.php';
///////////////////revisar os campos marcados e recoller os valores//////////////
if (!empty($_POST['tipo_noticia'])) {
        $id_tipo_noticia = $_POST['tipo_noticia'];
    if (!empty($_POST['titulo'])) {
        $titulo = $_POST['titulo'];
        if (!empty($_POST['resumo'])) {
            $resumo = $_POST['resumo'];
            if (!empty($_POST['contido'])) {
                $contido = $_POST['contido'];
///////////////////preparar as imaxes////////////////////
                if (isset($_POST['id_artigo'])) {
                    $id_artigo = $_POST['id_artigo'];
                } else {
                    $id_artigo = "";
                }
                if (isset($_FILES['imaxe'])) {
                    if ($_FILES['imaxe']['error'] === UPLOAD_ERR_OK) {
                        $ruta_temporal= $_FILES['imaxe']['tmp_name'];
                        $nome_arquivo= $_FILES['imaxe']['name'];
                        $extension= explode('.',($nome_arquivo));
                        $extension= strtolower(end($extension));
                        $extensions_validas = array ( 'jpg', 'gif', 'png', 'jpeg');
                        //revisar extensión
                        if (in_array($extension, $extensions_validas)) {
                            $directorio_subida = "../imaxes/noticias/";
                            $ruta_imaxe= $directorio_subida."noticia".$id_artigo."1.".$extension;
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

                if (isset($_FILES['imaxe2'])) {
                    if ($_FILES['imaxe2']['error'] === UPLOAD_ERR_OK) {
                        $ruta_temporal = $_FILES['imaxe2']['tmp_name'];
                        $nome_arquivo = $_FILES['imaxe2']['name'];
                        $extension = explode('.',($nome_arquivo));
                        $extension = strtolower(end($extension));
                        $extensions_validas = array ( 'jpg', 'gif', 'png', 'jpeg');
                        if (in_array($extension, $extensions_validas)) {
                            $directorio_subida = "../imaxes/noticias/";
                            $ruta_imaxe= $directorio_subida."noticia".$id_artigo."2.".$extension;
                            $imaxe2 = $ruta_imaxe;
                            move_uploaded_file($ruta_temporal, $ruta_imaxe ) ;
                        } else {
                            echo "
                                <script>
                                    alert('A extensión da imaxe debe ser jpg, gif, png ou jpeg');
                                    window.history.go(-1);
                                </script>";
                            exit;
                        }
                    } elseif ($_FILES['imaxe2']['error'] === 4) {
                        $imaxe2 = "";
                    } else {
                        echo "algo fallou ao subir a imaxe";
                    }
                } else {
                    $imaxe2 = "";
                }

            ////////////////limpar datos////////////////

                $titulo = limpar_datos($titulo);
                $resumo = htmlentities(stripslashes(strip_tags($resumo)));
                $contido = htmlentities(stripslashes(strip_tags($contido)));
                if (isset($_POST['contido2'])) {
                    $contido2 = $_POST['contido2'];
                    $contido2 = htmlentities(stripslashes(strip_tags($contido2)));
                } else {
                    $contido2 = "";
                }


                $autor = $_SESSION['user_fullname'];
                $id_autor = $_SESSION['user_id'];
            /////////////////inserción na base de datos///////////////////
                $datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
                $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                if ($_POST['action'] == 'Gardar') {
                    if ($db) {
                        if (isset($_POST['id_artigo'])) {
                            if (($imaxe == "") && ($imaxe2 == "")) {
                                //consultas preparadas (inserción de gran cantidade de datos)
                                $consulta = "UPDATE noticias SET titulo=?, resumo=?, contido=?, contido2=?
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta_preparada = mysqli_prepare($db, $consulta);
                                mysqli_stmt_bind_param($consulta_preparada, 'sbbb', $titulo, $resumo, $contido, $contido2);
                                mysqli_stmt_send_long_data($consulta_preparada,1,$resumo);
                                mysqli_stmt_send_long_data($consulta_preparada,2,$contido);
                                mysqli_stmt_send_long_data($consulta_preparada,3,$contido2);
                            } elseif ($imaxe == "") {
                                $consulta = "UPDATE noticias SET titulo=?, resumo=?, contido=?, imaxe2=?, contido2=?
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta_preparada = mysqli_prepare($db, $consulta);
                                mysqli_stmt_bind_param($consulta_preparada, 'sbbsb', $titulo, $resumo, $contido, $imaxe2, $contido2);
                                mysqli_stmt_send_long_data($consulta_preparada,1,$resumo);
                                mysqli_stmt_send_long_data($consulta_preparada,2,$contido);
                                mysqli_stmt_send_long_data($consulta_preparada,4,$contido2);
                            } elseif ($imaxe2 == "") {
                                $consulta = "UPDATE noticias SET titulo=?, resumo=?, contido=?, imaxe=?, contido2=?
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta_preparada = mysqli_prepare($db, $consulta);
                                mysqli_stmt_bind_param($consulta_preparada, 'sbbsb', $titulo, $resumo, $contido, $imaxe, $contido2);
                                mysqli_stmt_send_long_data($consulta_preparada,1,$resumo);
                                mysqli_stmt_send_long_data($consulta_preparada,2,$contido);
                                mysqli_stmt_send_long_data($consulta_preparada,4,$contido2);
                            } else {
                                $consulta = "UPDATE noticias SET titulo='$titulo', resumo='$resumo', imaxe='$imaxe', contido='$contido', imaxe2='$imaxe2', contido2='$contido2'
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta = "UPDATE noticias SET titulo=?, resumo=?, imaxe=?, contido=?, imaxe2=?, contido2=?
                                where id_artigo = '".$_POST['id_artigo']."'";
                                $consulta_preparada = mysqli_prepare($db, $consulta);
                                mysqli_stmt_bind_param($consulta_preparada, 'sbsbsb', $titulo, $resumo, $imaxe, $contido, $imaxe2, $contido2);
                                mysqli_stmt_send_long_data($consulta_preparada,1,$resumo);
                                mysqli_stmt_send_long_data($consulta_preparada,3,$contido);
                                mysqli_stmt_send_long_data($consulta_preparada,5,$contido2);
                            }
                        } else {
                            $consulta = "INSERT into noticias (titulo, resumo, imaxe, contido, imaxe2, contido2, id_usuario) values (?, ?, ?, ?, ?, ?, ?)";
                            $consulta_preparada = mysqli_prepare($db, $consulta);
                            mysqli_stmt_bind_param($consulta_preparada, 'sbsbsbi', $titulo, $resumo, $imaxe, $contido, $imaxe2, $contido2, $id_autor);
                            mysqli_stmt_send_long_data($consulta_preparada,1,$resumo);
                            mysqli_stmt_send_long_data($consulta_preparada,3,$contido);
                            mysqli_stmt_send_long_data($consulta_preparada,5,$contido2);
                        }
                        $exec_consulta = mysqli_stmt_execute($consulta_preparada);
                        if ($exec_consulta) {
                            if (isset($_POST['insertando'])) {
                            //se INSERTANDO, obter o id da nova noticia insertada para engadilo en noticias_tipo_noticia:
                            $consulta_id_artigo = "SELECT MAX(id_artigo) as id_artigo from noticias";
                            $exec_consulta_id_artigo = mysqli_query($db, $consulta_id_artigo);
                            $id_artigo_array = mysqli_fetch_assoc($exec_consulta_id_artigo);
                            $id_artigo = $id_artigo_array['id_artigo'];
                            $consulta_ntn = "INSERT into noticias_tipo_noticia (id_artigo, id_tipo_noticia) values (?, ?)";
                            $consulta_preparada_ntn = mysqli_prepare($db, $consulta_ntn);
                            mysqli_stmt_bind_param($consulta_preparada_ntn, "ii", $id_artigo, $id_tipo_noticia);
                            // "ss" porque se van a introducir dous strings, ssi -> 2  strings e 1 integer, etc.
                            } else {
                                //se modificando, podemos insertar directamente na táboa noticias_tipo_noticia:
                                $consulta_ntn = "UPDATE noticias_tipo_noticia SET id_tipo_noticia=? where id_artigo=?";
                                $consulta_preparada_ntn = mysqli_prepare($db, $consulta_ntn);
                                mysqli_stmt_bind_param($consulta_preparada_ntn, "ii", $id_tipo_noticia, $id_artigo);
                            }
                            $exec_consulta_ntn = mysqli_stmt_execute($consulta_preparada_ntn);
                            if ($exec_consulta_ntn) {
                                echo "<p class='header_updates'>Inserción realizada correctamente</p>";
                                $contido = $titulo = $resumo = $contido2 = $id_tipo_noticia = "";
                            } else {
                                 echo "<p class='header_updates'>Erro ao inserir datos.</p>";
                            }
                        }
                    }
                mysqli_close($db);
                } elseif ($_POST['action'] == 'Eliminar') {
                    $consulta_borrar = "DELETE FROM noticias where id_artigo=$id_artigo";
                    $borrar = mysqli_query($db, $consulta_borrar);
                    if ($borrar) {
                        echo "<p class='header_updates'>Noticia eliminada.</p>";
                        $contido = $titulo = $resumo = $contido2 = "";
                    } else {
                        echo "<p class='header_updates'>Houbo un problema ao intentar eliminar a noticia.</p>";
                    }
                }
            } else {
                $contido_err = "A noticia debe ter un corpo.";
            }
        } else {
                $resumo_err = "Debes incluír un resumo";
            }
        } else {
            $titulo_err = "Introduce un título";
    }
} else {
        $tipo_err = "A noticia debe pertencer a unha categoría.";
}
?>
