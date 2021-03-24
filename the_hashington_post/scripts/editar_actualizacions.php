<?php
include '../paxinas/header_ext.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI']; // volver á páxina anterior ao facer login/logout
include '../paxinas/aside.php';
include 'insertar_actualizacions.php';
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);

if (isset($_GET["so"])) {
  $so = $_GET["so"];
}
if (isset($_GET['accion'])) { // campo presente no link da páxina anterior
    $accion=$_GET['accion'];
}


if (!isset($_POST['accion'])) {
    $_POST['accion'] = "empty";
}
if (!isset($_POST["id_artigo"]) OR ($_POST['accion'] == 'Eliminar')) {
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta = "SELECT * FROM actualizacions_software where sistema_operativo = '".$so."' limit 1";
        $res_consulta = mysqli_query($db, $consulta);
        if ($res_consulta) {
            while ($actualizacions_software = mysqli_fetch_assoc($res_consulta)) {
            $id_artigo = $actualizacions_software['id_artigo'];
            }
        }
    }

} else {
    $id_artigo = $_POST["id_artigo"];
 }


  if (isset($_GET["num_version"])) {
    $num_version = $_GET["num_version"];
  }

switch ($accion) {
    case 'editar':
    echo "
    <section>
        <article class='art_completo2'>
            <form method='POST' action='' enctype='multipart/form-data'>
                <p class='header_updates3'>Editando as actualizaci&oacute;ns</p><br><br>
                <h2 class='upd'>Selecciona que actualizaci&oacute;ns queres modificar</h2>
                <select name='so' class='oculto'>
                    <option hidden value='".$so."'></option>
                </select>
                <select onchange='this.form.submit()' name='id_artigo' class='selectorupd'>";
        $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
        if ($db) {
            $consulta = "SELECT * FROM actualizacions_software where sistema_operativo = '".$so."'";
            $res_consulta = mysqli_query($db, $consulta);
            if ($res_consulta) {
                while ($actualizacions_software = mysqli_fetch_assoc($res_consulta)) {
                    if ($actualizacions_software['id_artigo'] == $id_artigo) {
                        echo "  <option selected value='".$actualizacions_software['id_artigo']."'>$so ".$actualizacions_software['num_version']."</option>";
                    } else {        // amosar no <select> a actualización que está cargada por defecto
                        echo "  <option value='".$actualizacions_software['id_artigo']."'>$so ".$actualizacions_software['num_version']."</option>";
                    }
                }
            }
        }
        mysqli_close($db);
        echo "       </select>
                        <input type='hidden' class='botonconf_b' name='cargar' value='Cargar'>";
        $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
        if ($db) {
            $consulta = "SELECT * FROM actualizacions_software where sistema_operativo = '".$so."'";
            $res_consulta = mysqli_query($db, $consulta);
            if ($res_consulta) {
                while ($actualizacions_software = mysqli_fetch_assoc($res_consulta)) {
                    if ($actualizacions_software['id_artigo'] == $id_artigo) {
                        echo "<textarea maxlength='75' class='header_updates2' name='titulo'>".$actualizacions_software['titulo']."</textarea>";
                        echo "        <textarea class='form_contido2' name='contido'>".$actualizacions_software['contido']."</textarea>
                                      <p class='meta_updates'>".$actualizacions_software['num_version']." | ".$actualizacions_software['data_saida']."</p>
                                      <div class='botonconf_d'>
                                          <input type='submit' class='botonconf_b' name='accion' value='Eliminar'>
                                      </div>";
                    }
                }
            }
        }
        mysqli_close($db);
        break;
        case 'insertar':
            echo "
            <section>
                <article class='art_completo2'>
                    <form method='POST' action=''>
                        <p class='header_updates3'>Insertando nova actualizaci&oacute;n de ".$so." linux</p><br><br>
                        <p class='h4err'>".$titulo_err."<p>
                        <textarea maxlength='75' class='header_updates2' maxlength='25' placeholder='titulo da actualización' name='titulo'>".$titulo."</textarea>
                        <p class='h4err'>".$contido_err."<p>
                       <textarea class='form_contido2' placeholder='Escribe aquí o contido' name='contido'>".$contido."</textarea>
                       <p class='meta_updates'></p>
                       <p class='h4err'>".$num_ver_err."<p>
                       <input type='text' name='num_version' maxlength='4' placeholder='Indica o número de versión (ex: 1.07, 2.45, 1.31...)'></input>
                       <input type='hidden' name='sistema_operativo' value='".$so."'>";
        break;
}
echo "          <div class='botonconf_d'>
                    <input type='submit' class='botonconf_b' name='accion' value='Gardar'>
                </div>
            </form>
            <div class='atras'>
                          <a href='../paxinas/$so.php?so=$so'><i class='fa fa-angle-double-left'></i></a>
            </div>
        </article>
    </section>";

     include '../paxinas/footer.php';
?>
