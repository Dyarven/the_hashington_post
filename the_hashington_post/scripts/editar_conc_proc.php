<?php
include '../paxinas/header_ext.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI']; // volver á páxina anterior ao facer login/logout
include '../paxinas/aside_ext.php';
include 'insertar_conc_proc.php';
$datos_conexion = parse_ini_file('./ini/datos_conexion.ini',true);
echo "
        <section>
            <form method='POST' action='' enctype='multipart/form-data'>
              <article class='art_completo'>";

if ((isset($_GET['id_artigo'])) AND (!isset($_POST['accion']))) {
    $id_artigo = $_GET['id_artigo'];
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta_selcp = "SELECT categoría from concep_proced where id_artigo = $id_artigo";
        $res_consulta_selcp = mysqli_query($db, $consulta_selcp);
        $selcp_array = mysqli_fetch_row($res_consulta_selcp);
        $selcp = $selcp_array[0];
    }
    mysqli_close($db);
} elseif ((!isset($_GET['id_artigo'])) AND (!isset($_POST['accion']))) {
   $id_artigo = "";
   $selcp = $_GET['selcp'];
}

    if (!isset($_POST['accion'])) {
        $_POST['accion'] = "";
    }
if ($_POST['accion'] != 'Eliminar') {
    switch($selcp) {
        case "concepto":
              echo "<div class='busqueda2'>
                        <div class='filtro'>
                            <input type='radio' id='proc' name='selcp' value='procedemento' class='oculto'>
                            <label class='lab2' for='proc'><b>Procedemento</b></label>
                        </div>
                        <div class='filtro'>
                            <input type='radio' id='conc' name='selcp' value='concepto' class='oculto' checked='checked'>
                            <label class='lab2' for='conc'><b>Concepto</b></label>
                        </div>
                    </div>";
              break;
         case "procedemento":
                echo "<div class='busqueda2'>
                          <div class='filtro'>
                              <input type='radio' id='proc' name='selcp' value='procedemento' class='oculto' checked='checked'>
                              <label class='lab2' for='proc'><b>Procedemento</b></label>
                          </div>
                          <div class='filtro'>
                              <input type='radio' id='conc' name='selcp' value='concepto' class='oculto'>
                              <label class='lab2' for='conc'><b>Concepto</b></label>
                          </div>
                      </div>";
                break;
    }
}
if (!isset($_GET['id_artigo'])) {
            echo "
            <div class='custom-input-file col-md-6 col-sm-6 col-xs-6'>
                 Engadir imaxe
                <input type='file' class='input-file' name='imaxe_preview' value='' id='imaxe_preview'/>
            </div>
                <textarea minlength='3' maxlength='19' class='form_titulo' name='titulo' placeholder='Nome do concepto/procedemento'>".$titulo."</textarea>
                <p class='header_updates2'>Definición Preview</p><br><br>
                <textarea minlegth='260' maxlength='340' class='form_resumo' placeholder='Definición (preview)' name='definicion_preview'>".$definicion_preview."</textarea>
                <div class='custom-input-file col-md-6 col-sm-6 col-xs-6'>
                     Engadir imaxe
                    <input type='file' class='input-file' name='imaxe' value='' id='imaxe'/>
                </div>
                <p class='header_updates2'>Descripción detallada</p><br><br>
                <textarea class='form_contido' minlength='800' placeholder='Descripción ao detalle' name='contido'>".$contido."</textarea>
                <div class='botonconf_c'>
                    <input type='submit' class='botonconf_b' name='accion' value='Gardar'>
                </div>";
} elseif ($_POST['accion'] == 'Eliminar') {
    echo "<section class='sec_6'>";
    echo "    <article class='header_erro'></article>";
    echo "    <article class='header_sec6'><a class='linksw2' href='../paxinas/conceptos_e_procedementos.php?selcp=$selcp'>Preme aquí para voltar á páxina de inicio</a></article>";
    echo "</section class='sec_principalerr'>";

} else {
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta = "SELECT * FROM concep_proced WHERE categoría = '".$selcp."'";
        $res_consulta = mysqli_query($db, $consulta);
        if ($res_consulta) {
            while ($concep_proced = mysqli_fetch_assoc($res_consulta)) {
                if ($concep_proced['id_artigo'] == $id_artigo) {
                    echo "
                    <div class='custom-input-file col-md-6 col-sm-6 col-xs-6'>
                         Engadir imaxe
                        <input type='file' class='input-file' name='imaxe_preview' value='".$concep_proced['imaxe_preview']."' id='imaxe_preview'/>
                    </div>
                    <p class='imgside'>".$concep_proced['imaxe_preview']."</p>
                    <textarea minlength='3' maxlength='19' class='form_titulo' name='titulo' placeholder='Nome do concepto/procedemento'>".$concep_proced['titulo']."</textarea>
                    <p class='header_updates2'>Definición Preview</p><br><br>
                    <textarea minlegth='260' maxlength='340' class='form_resumo' name='definicion_preview' placeholder='Definición (preview)'>".$concep_proced['definicion_preview']."</textarea>
                    <div class='custom-input-file col-md-6 col-sm-6 col-xs-6'>
                         Engadir imaxe
                        <input type='file' class='input-file' name='imaxe' value='".$concep_proced['imaxe']."' id='imaxe'/>
                    </div>
                    <p class='imgside'>".$concep_proced['imaxe']."</p>
                    <p class='header_updates2'>Descripción detallada</p><br><br>
                    <textarea class='form_contido' name='contido' minlength='800' placeholder='Descripción ao detalle'>".$concep_proced['contido']."</textarea>
                    <input type='hidden' name='id_artigo' value='".$concep_proced['id_artigo']."'/>
                    <div class='botonconf_c'>
                        <input type='submit' class='botonconf_b' name='accion' value='Gardar'>
                    </div>
                    <div class='botonconf_c'>
                        <input type='submit' class='botonconf_b' name='accion' value='Eliminar'>
                    </div>";
                }
            }
        }
    }
    mysqli_close($db);
}
        echo "
            </form>
        </article>
    </section>";

     include '../paxinas/footer.php';
?>
