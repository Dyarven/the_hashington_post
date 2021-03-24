<?php
include '../paxinas/headeradm_ext.php';
include '../scripts/control_usuarios.php';
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);

if (!isset($_POST['accion'])) { //para que non saia o erro "undefined index ao empregar o selector"
    $_POST['accion'] = "empty";
}

if ((!isset($_POST['id_usuario'])) OR ($_POST['accion'] == 'eliminar')) {
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta = "SELECT * FROM usuarios limit 1";
        $res_consulta = mysqli_query($db, $consulta);
        if ($res_consulta) {
            while ($usuarios = mysqli_fetch_assoc($res_consulta)) {
                $id_usuario = $usuarios['id_usuario'];
            }
        }
    }
    mysqli_close($db);
} else {
     $id_usuario = $_POST['id_usuario'];
  }

?>
        <main>
            <div class='cpanel'>
                <br/><br/><br/>
                <button type='button' class='back2'><a href='editar_tipo_noticias.php'>Editar categorías</a></button>
                <br/><br/><br/>
              <h1>Engadir editor</h1>
               <div><?php echo $exito_ins;?></div>
              <form method='post' id='engadir' action='<?php
                 echo htmlspecialchars($_SERVER['PHP_SELF']);?>'>
                  <input type='text' name='nome_usuario' placeholder='Usuario' required='required'/>
                  <div><?php echo $usuarioErr2;?></div> <!--en caso de erro enche a variable err e amosa esto-->
                  <input type='text' name='nome_completo' placeholder='Nome completo' required='required'/>
                  <div><?php echo $nome_completoErr2;?></div>
                  <input type='password' name='contrasinal' placeholder='Contrasinal temporal' required='required' value=''/>
                  <div><?php echo $contrasinalErr;?></div>
                  <input type='email' name='email' placeholder='Correo electrónico' required='required'/>
                  <div><?php echo $emailErr2;?></div>
                  <button type='submit' name='accion' value='engadir' id='engadir' class='btnadm'>Engadir</button>
              </form>
                  <br/><br/><br/>
              <h1>Modificar editor</h1>
              <?php echo $exito; ?>
              <form method='POST' id='modificar' action='<?php
                 echo htmlspecialchars($_SERVER['PHP_SELF']);?>'>
<?php
echo "             <select onchange='this.form.submit()' name='id_usuario' class='selectorusr'>";

$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta2 = "SELECT * FROM usuarios where tipo_usuario != 'administrador'";
    $res_consulta2 = mysqli_query($db, $consulta2);
    if ($res_consulta2) {
        while ($usuarios = mysqli_fetch_assoc($res_consulta2)) {
            if ($usuarios['id_usuario'] == $id_usuario) {
                echo "<option selected value='".$usuarios['id_usuario']."'>".$usuarios['nome_usuario']."</option>";
            } else {
                echo "<option value='".$usuarios['id_usuario']."'>".$usuarios['nome_usuario']."</option>";
            }
        }
    }
}
mysqli_close($db);
echo "            </select>
                  <input type='hidden' class='btnadm' name='cargar_usuario' value='cargar_usuario'/>";
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta3 = "SELECT * FROM usuarios";
        $res_consulta3 = mysqli_query($db, $consulta3);
        if ($res_consulta3) {
            while ($usuarios = mysqli_fetch_assoc($res_consulta3)) {
                if ($usuarios['id_usuario'] == $id_usuario) {
                    echo "
                        <input type='text' name='nome_usuario' placeholder='Usuario' value='".$usuarios['nome_usuario']."'/>
                        $usuarioErr
                        <input type='text' name='nome_completo' placeholder='Nome completo' value='".$usuarios['nome_completo']."'/>
                        $nome_completoErr
                        <input type='email' name='email' placeholder='Correo electrónico' value='".$usuarios['email']."'/>
                        $emailErr
                        <input type='password' name='contrasinal' placeholder='Novo contrasinal temporal' value=''/>";
                }
            }
        }
    }

    mysqli_close($db);
echo "            <button type='submit' class='btnadm' name='accion' value='modificar' id='modificar'>Modificar</button>
              </form>";
?>
                <br/><br/><br/>
               <h1>Eliminar editor</h1>
               <div><?php echo $exito_del;?></div>
              <form method='post' id='eliminar' action='<?php
                 echo htmlspecialchars($_SERVER['PHP_SELF']);?>'>
<?php
echo "             <select onchange='this.form.submit()' name='id_usuario' class='selectorusr'>";
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta2 = "SELECT * FROM usuarios where tipo_usuario != 'administrador'";
    $res_consulta2 = mysqli_query($db, $consulta2);
    if ($res_consulta2) {
        while ($usuarios = mysqli_fetch_assoc($res_consulta2)) {
            if ($usuarios['id_usuario'] == $id_usuario) {
                echo "<option selected value='".$usuarios['id_usuario']."'>".$usuarios['nome_usuario']."</option>";
            } else {
                echo "<option value='".$usuarios['id_usuario']."'>".$usuarios['nome_usuario']."</option>";
            }
        }
    }
}
mysqli_close($db);
echo "            </select>
                  <input type='hidden' class='btnadm' name='cargar_usuario' value='cargar_usuario'/>";
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta3 = "SELECT * FROM usuarios";
    $res_consulta3 = mysqli_query($db, $consulta3);
    if ($res_consulta3) {
        while ($usuarios = mysqli_fetch_assoc($res_consulta3)) {
            if ($usuarios['id_usuario'] == $id_usuario) {
                echo "
                  <input readonly type='text' name='usuario' placeholder='Usuario' required='required' value='".$usuarios['nome_usuario']."'/>
                  <input readonly type='email' name='email' placeholder='Correo electrónico' value='".$usuarios['email']."' required='required'/>
                  <button type='submit' class='btnadm' name='accion' value='eliminar' id='eliminar'>Eliminar</button>
              </form>
                <br/><br/><br/>
                <button type='button' class='back'><a href='../inicio_admin.php'>Volver á páxina principal</a></button>
          </div>";
            }
        }
    }
}
mysqli_close($db);

        include '../paxinas/footer.php';
?>
