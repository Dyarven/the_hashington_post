<?php
include 'header.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI'];
include 'aside.php';
$datos_conexion = parse_ini_file('../scripts/ini/datos_conexion.ini',true);

if (!isset($_GET['selcp'])) { $selcp = 'concepto';
} else {
  $selcp = $_GET['selcp'];
  }

if (isset($_GET['id_artigo'])) {
  $id_artigo = $_GET['id_artigo'];
}

$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta = "SELECT * from concep_proced";
    $res_consulta = mysqli_query($db, $consulta);
    if ($res_consulta) {
        while ($concep_proced = mysqli_fetch_assoc($res_consulta)) {
            if ($concep_proced['id_artigo'] == $id_artigo) {
                if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
                    echo "
                         <div class='botonconf_a'>
                              <b><a href='../scripts/editar_conc_proc.php?id_artigo=".$concep_proced['id_artigo']."' class='botonconf_b'>Editar</a></b>
                          </div>";
                }
        echo "<section>
                 <article class='art_completo'>
                     <h2 class='header_noticias2'>".$concep_proced['titulo']."</h2>
                     <br/>";
            if ((!empty($concep_proced['imaxe'])) AND (!is_null($concep_proced['imaxe']))) {
                echo "<img class='img_noticia' src='".$concep_proced['imaxe']."'>";
            }
                echo "<p class='art_contido'>".$concep_proced['contido']."</p>";
            }
        }
    }
}
                echo "
                     <div class='atras'>
                          <a href='conceptos_e_procedementos.php?selcp=".$selcp."'><i class='fa fa-angle-double-left'></i></a>
                     </div>
                </article>
             </section>";



include 'footer.php';

?>
