<?php
$datos_conexion = parse_ini_file('./ini/datos_conexion.ini',true);
include '../paxinas/header_ext.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI']; // volver á páxina anterior ao facer login/logout
include '../paxinas/aside_ext.php';
include 'insertar_noticia.php';
echo "
<section>
    <article class='art_completo'>
        <form method='POST' action='' enctype='multipart/form-data'>";
if (!isset($_POST['action'])) {
    $_POST['action'] = "";
}
if (!isset($_GET['noticia'])) {
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta_tn = "SELECT * from tipo_noticia";
    $res_consulta_tn = mysqli_query($db, $consulta_tn);
    if ($res_consulta_tn) {
        echo "<p class='header_tns'>Selecciona un tipo de noticia</p>";
        while ($tipo_noticia = mysqli_fetch_assoc($res_consulta_tn)) {
            echo "
                   <div class='busqueda2'>
                      <div class='filtro'>";
                      if ($tipo_noticia['id_tipo_noticia'] == $id_tipo_noticia) {
                          echo "<input checked type='radio' id='".$tipo_noticia['tipo_noticia']."' name='tipo_noticia' value='".$tipo_noticia['id_tipo_noticia']."' class='oculto'>";
                      } else {
                          echo "<input type='radio' id='".$tipo_noticia['tipo_noticia']."' name='tipo_noticia' value='".$tipo_noticia['id_tipo_noticia']."' class='oculto'>";
                      }
                        echo "<label class='lab2' for='".$tipo_noticia['tipo_noticia']."'><b>".$tipo_noticia['tipo_noticia']."</b></label>
                      </div>
                  </div>";
        }
    }
}
mysqli_close($db);
  echo "<h4 class='h4err'>".$titulo_err."</h4>";
  echo "<textarea minlength='10' maxlength='70' class='form_titulo' placeholder='Titulo' name='titulo'>".$titulo."</textarea>";
echo "<h4 class='h4err'>".$resumo_err."</h4>";
  echo "<p class='header_updates2'>Resumo</p><br><br>";
  echo "<textarea minlength='700' maxlength='1000' class='form_resumo' name='resumo' placeholder='Resumo da noticia para a páxina principal'>".$resumo."</textarea>
        <div class='custom-input-file col-md-6 col-sm-6 col-xs-6'>
             Engadir imaxe
            <input type='file' class='input-file' name='imaxe' value='' id='imaxe'/>
        </div>";
  echo "<h4 class='h4err'>".$contido_err."</h4>";
  echo "<p class='header_updates2'>Contido</p><br><br>";
  echo "<textarea class='form_contido' minlength='1500' placeholder='Corpo da noticia' name='contido'>".$contido."</textarea>
        <div class='custom-input-file col-md-6 col-sm-6 col-xs-6'>
             Engadir imaxe
            <input type='file' class='input-file' name='imaxe2' value='' id='imaxe2'/>
        </div>
        <textarea class='form_contido' placeholder='Continuaci&oacute;n da noticia' name='contido2'>".$contido2."</textarea>
        <p class='meta_noticia'>autor | data actual</p>
        <input type='hidden' name='insertando' value='insertando'/>
        </p>";
            echo "
                <div class='botonconf_c'>
                    <input type='submit' class='botonconf_b' id='action' name='action' value='Gardar'>
                </div>
                <div class='botonconf_c'>
                    <input type='submit' class='botonconf_b' id='action' name='action' value='Eliminar'>
                </div>
            </form>
        </article>
    </section>";

} elseif ($_POST['action'] == 'Eliminar') {
    echo "<section class='sec_6'>";
    echo "    <article class='header_erro'></article>";
    echo "    <article class='header_sec6'><a class='linksw2' href='../inicio_admin.php'>Preme aquí para voltar á páxina de inicio</a></article>";
    echo "</section class='sec_principalerr'>";

} else {
    $noticia = $_GET['noticia'];
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta = "SELECT tipo_noticia.tipo_noticia from (tipo_noticia
        inner join noticias_tipo_noticia ntn
        inner join noticias n on n.id_artigo = ntn.id_artigo AND tipo_noticia.id_tipo_noticia = ntn.id_tipo_noticia)
        where n.id_artigo =  ".$noticia."";
        $res_consulta = mysqli_query($db, $consulta);
        $array_categoria = mysqli_fetch_assoc($res_consulta);
        $categoria = $array_categoria['tipo_noticia'];
        if ($categoria) {
            $consulta2 = "SELECT * from tipo_noticia";
            $res_consulta2 = mysqli_query($db, $consulta2);
            if ($res_consulta2) {
                while ($tipo_noticia = mysqli_fetch_assoc($res_consulta2)) {
                    echo " <div class='busqueda2'>
                              <div class='filtro'>";
                    if ($categoria == $tipo_noticia['tipo_noticia']) {
                        echo "  <input checked type='radio' id='".$tipo_noticia['tipo_noticia']."' name='tipo_noticia' value='".$tipo_noticia['id_tipo_noticia']."' class='oculto'>";
                    } else {
                        echo " <input type='radio' id='".$tipo_noticia['tipo_noticia']."' name='tipo_noticia' value='".$tipo_noticia['id_tipo_noticia']."' class='oculto'>";
                    }
                    echo "
                              <label class='lab2' for='".$tipo_noticia['tipo_noticia']."'><b>".$tipo_noticia['tipo_noticia']."</b></label>
                            </div>
                        </div>";
                }
            }
        }
    }
    mysqli_close($db);

    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta3 = "SELECT n.id_artigo, n.titulo, n.resumo, n.contido, n.contido2, n.imaxe, n.imaxe2, u.nome_completo as autor, n.data_publicacion
        from (noticias n inner join usuarios u on n.id_usuario = u.id_usuario)";
        $res_consulta3 = mysqli_query($db, $consulta3);
        if ($res_consulta3) {
            while ($noticias = mysqli_fetch_assoc($res_consulta3)) {
                if ($noticias['id_artigo'] == $noticia) {
                  echo "<textarea minlength='10' maxlength='70' class='form_titulo' placeholder='Titulo' name='titulo'>".$noticias['titulo']."</textarea>";
                  echo "<p class='header_updates2'>Resumo</p><br><br>";
                  echo "<textarea minlength='700' maxlength='1000' class='form_resumo' name='resumo' placeholder='Resumo'>".$noticias['resumo']."</textarea>
                  <div class='custom-input-file col-md-6 col-sm-6 col-xs-6'>
                       Engadir imaxe
                      <input type='file' class='input-file' name='imaxe' value='".$noticias['imaxe']."' id='imaxe'/>
                  </div>
                  <p class='imgside'>".$noticias['imaxe2']."</p>";
                  echo "<p class='header_updates2'>Contido</p><br><br>";
                  echo "<textarea class='form_contido' name='contido'  minlength='1500' placeholder='Corpo da noticia'>".$noticias['contido']."</textarea>
                        <div class='custom-input-file col-md-6 col-sm-6 col-xs-6'>
                             Engadir imaxe
                            <input type='file' class='input-file' name='imaxe2' value='".$noticias['imaxe2']."' id='imaxe2'/>
                        </div>
                        <p class='imgside'>".$noticias['imaxe2']."</p>
                        <textarea class='form_contido' name='contido2' placeholder='Continuación da noticia'>".$noticias['contido2']."</textarea>
                        <p class='meta_noticia'>".$noticias['autor']." | ".$noticias['data_publicacion']."</p>
                        <input type='hidden' name='autor' value='".$noticias['autor']."'/>
                        <input type='hidden' name='data_publicacion' value='".$noticias['data_publicacion']."'/>
                        <input type='hidden' name='id_artigo' value='".$noticias['id_artigo']."'/>";
                    echo "
                        <div class='botonconf_c'>
                            <input type='submit' class='botonconf_b' id='action' name='action' value='Gardar'>
                        </div>
                        <div class='botonconf_c'>
                            <input type='submit' class='botonconf_b' id='action' name='action' value='Eliminar'>
                        </div>
                    </form>
                </article>
            </section>";
                }
            }
        }
    }
}
echo "
</section>";
     include '../paxinas/footer.php';
?>
