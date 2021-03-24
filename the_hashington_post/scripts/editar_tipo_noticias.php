<?php
include '../paxinas/headeradm_ext.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI']; // volver á páxina anterior ao facer login/logout
include 'funcions_control.php';
$datos_conexion = parse_ini_file('./ini/datos_conexion.ini',true);
$categoria_err = $categoria_existe = $exito_borrar = $existen_noticias = $exito = $empty = "";

//control
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['accion'])) {
        $_POST['accion'] = "";
    } else {
        if (($_POST['accion']) == 'Engadir') {
            if (empty($_POST['categoria'])) {
                $categoria_err = "introduce un nome para a categoría";
            } else {
                $categoria = $_POST['categoria'];
                $categoria = limpar_datos($categoria);
                $categoria = preg_replace('/[^a-zA-Z]+/', '', $categoria);
                $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                if ($db) {
                    $consulta = "SELECT * FROM tipo_noticia where tipo_noticia = $categoria";
                    $res_consulta = mysqli_query($db, $consulta);
                    if ($res_consulta) {
                        $categoria_existe = "Esa categoría xa existe";
                    } else {
                        $consulta2 = "INSERT INTO tipo_noticia (tipo_noticia) values ('".$categoria."')";
                        $res_consulta2 = mysqli_query($db, $consulta2);
                        $exito = "A categoría foi insertada con exito";
                    }
                } mysqli_close($db);
            }
        } elseif (($_POST['accion']) == 'Eliminar') {
            if (isset($_POST['categoria'])) {
                $categoria = $_POST['categoria'];
                $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
                if ($db) {
                    $consulta = "SELECT * from noticias n
                    inner join usuarios u on n.id_usuario = u.id_usuario
                    inner join noticias_tipo_noticia ntn on ntn.id_artigo = n.id_artigo
                    inner join tipo_noticia tn on tn.id_tipo_noticia = ntn.id_tipo_noticia
                    where tn.tipo_noticia ='".$categoria."'";
                    $consulta_count = "SELECT count(*) from noticias n
                    inner join usuarios u on n.id_usuario = u.id_usuario
                    inner join noticias_tipo_noticia ntn on ntn.id_artigo = n.id_artigo
                    inner join tipo_noticia tn on tn.id_tipo_noticia = ntn.id_tipo_noticia
                    where tn.tipo_noticia ='".$categoria."'";
                    $res_consulta = mysqli_query($db, $consulta);
                    $res_consulta_count = mysqli_query($db, $consulta_count);
                    $noticias_asociadas = mysqli_fetch_row($res_consulta_count);
                    if ($noticias_asociadas[0] > 0) {
                        $existen_noticias = "Non se pode eliminar unha categoría con noticias asociadas";
                    } else {
                        $consulta2 = "DELETE FROM tipo_noticia where tipo_noticia = '".$categoria."'";
                        $res_consulta2 = mysqli_query($db, $consulta2);
                        $exito_borrar = "A categoría foi eliminada correctamente";

                    }
                }
            } else {
                $categoría = "";
                $empty = "Nada que eliminar";
            }
        }
    }
}

?>
<main>
    <div class='cpanel2'>
        <div class='cpaneldiv'>
        <h1>Engadir categoría</h1>
            <form method='post' id='engadir' action='<?php
               echo htmlspecialchars($_SERVER['PHP_SELF']);?>'>
                <?php echo $exito;?><?php echo $categoria_err;?><?php echo $categoria_err;?>
                <input type='text' name='categoria' maxlength="15" placeholder='Nome da categoría' value='' required='required'/>
                <div></div><div>
                <button type='submit' class='btnadm' name='accion' value='Engadir' id='Engadir'>Engadir</button>
            </form>
        </div>

            <br/><br/><br/>
        <h1>Eliminar categoría</h1>
        <p><i>(non se poden borrar as 4 principais)</i></p>
<form method='POST' id='eliminar' action='<?php
   echo htmlspecialchars($_SERVER['PHP_SELF']);?>'>
   <?php echo $exito_borrar;?><?php echo $existen_noticias;?><?php echo $empty;?>
<?php
echo "             <select name='categoria' class='selectorusr'>";

$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    //que non se poidan borrar as 4 categorías orixinais
    $consulta2 = "SELECT * FROM tipo_noticia where tipo_noticia not in('Malware','Vulnerabilidades', 'Ciberataques', 'Filtracions')";
    $res_consulta2 = mysqli_query($db, $consulta2);
    if ($res_consulta2) {
        while ($tipo_noticias = mysqli_fetch_assoc($res_consulta2)) {
              echo "<option value='".$tipo_noticias['tipo_noticia']."'>".$tipo_noticias['tipo_noticia']."</option>";
        }
    }
}
mysqli_close($db);
echo "            </select>
            <button type='submit' class='btnadm' name='accion' value='Eliminar' id='Eliminar'>Eliminar</button>
        </form>
        <br/><br/><br/>
        <button type='button' class='back'><a href='../inicio_admin.php'>Volver á páxina principal</a></button>
        <br/><br/><br/>
        <br/><br/><br/>
        <button type='button' class='back2'><a href='paneladmin.php'>Ir ao panel de administración</a></button>
    </div>
</main>";


include '../paxinas/footer.php';
?>
