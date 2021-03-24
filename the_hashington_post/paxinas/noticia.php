<?php
include 'header.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI'];
include 'aside.php';
$datos_conexion = parse_ini_file('../scripts/ini/datos_conexion.ini',true);

if (isset($_GET['noticia'])) {
  $noticia = $_GET['noticia'];
}
if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
  echo "      <div class='botonconf_a'>
                  <b><a href='../scripts/editar_noticia.php?noticia=".$noticia."' class='botonconf_b'>Editar</a></b>
              </div>";
}

$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta = "SELECT n.id_artigo, n.titulo, n.resumo, n.contido, n.contido2, n.imaxe, n.imaxe2, u.nome_completo as autor, n.data_publicacion
    from (noticias n inner join usuarios u on n.id_usuario = u.id_usuario)";
    $res_consulta = mysqli_query($db, $consulta);
    if ($res_consulta) {
        while ($noticias = mysqli_fetch_assoc($res_consulta)) {
            if ($noticias['id_artigo'] == $noticia) {
            echo "
                <section>
                    <article class='art_completo'>
                        <h2 class='header_noticias2'>".$noticias['titulo']."</h2>
                        <br/>
                        <article class='art_interior'>
                         <hr class='hr2'/>
                         <p class='art_contido'>".$noticias['resumo']."</p>
                         <hr class='hr2'/>";
                if ((!empty($noticias['imaxe'])) AND (!is_null($noticias['imaxe']))) {
                    echo " <img class='img_noticia' src='".$noticias['imaxe']."'>";
                }
                    echo " <p class='art_contido'>".$noticias['contido']."</p>";
                if ((!empty($noticias['imaxe2'])) AND (!is_null($noticias['imaxe2']))) {
                    echo " <img class='img_noticia' src='".$noticias['imaxe2']."'>";
                }
                echo "  <p class='art_contido'>".$noticias['contido2']."</p>";
                echo "  <p class='meta_noticia'>".$noticias['data_publicacion']." | ".$noticias['autor']."</p>";
            }
        }
    }
}
mysqli_close($db);
        echo "</article>";
            echo " <div class='atras'>";
            echo       "<a href='../inicio_admin.php'><i class='fa fa-angle-double-left'></i></a>";
            echo "</div>
            </article>
        </section>";

include 'footer.php';

?>
