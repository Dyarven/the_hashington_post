<?php
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
//xenera as variables buscarVulnerabilidades, buscarFiltracions... e ponas a false
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
//iniciar sesión
if (isset($_GET['search'])) {
    $busca = $_GET['search'];
} else {
    $busca ="";
}
if ($db) {
    $consulta = "select tipo_noticia from tipo_noticia";
    $res_consulta = mysqli_query($db, $consulta);
    if ($res_consulta) {
        $i=0;
        while ($tipo_noticias = mysqli_fetch_assoc($res_consulta)) {
            ${'buscar'.$tipo_noticias['tipo_noticia']} = false;
        $i++;
        }
    }
    mysqli_close($db);
}

if (isset($_GET['tipo_noticia'])) { //checkbox marcados
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    for ($i=0; $i < count($_GET['tipo_noticia']); $i++) {
        $tipo_noticia = $_GET['tipo_noticia'][$i];
        if ($db) {
            $consulta = "select * from tipo_noticia";
            $res_consulta = mysqli_query($db, $consulta);
            if ($res_consulta) {
                while ($consulta_tipo_noticia = mysqli_fetch_assoc($res_consulta)) {
                    $valor = $_GET['tipo_noticia'][$i];
                    $consulta2 = "select tipo_noticia from tipo_noticia where id_tipo_noticia = '$valor'";
                    ${'buscar'.$tipo_noticia} = mysqli_query($db, $consulta2);
                    if (${'buscar'.$tipo_noticia} == true) {
                        break;
                    }
                }
            }
        }
    }
    mysqli_close($db);
}

echo "
       <form class='busqueda' method='get' action=''>
           <input type='text' placeholder='Buscar unha noticia' name='search' maxlength='45' value='".$busca."'>";
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta = "select tipo_noticia from tipo_noticia";
        $res_consulta = mysqli_query($db, $consulta);
        if ($res_consulta) {
            while ($tipo_noticias = mysqli_fetch_assoc($res_consulta)) {
                if (${'buscar'.$tipo_noticias['tipo_noticia']}) { //amosar o checkbox como "checked"
        echo "<div class='filtro'>
                  <input onchange='this.form.submit();' checked type='checkbox' id='".$tipo_noticias['tipo_noticia']."' name='tipo_noticia[]' value='".$tipo_noticias['tipo_noticia']."' class='oculto'>
                  <label class='lab' for='".$tipo_noticias['tipo_noticia']."'><b>".$tipo_noticias['tipo_noticia']."</b></label>
              </div>";
                } else {
        echo "<div class='filtro'>
                 <input type='checkbox' onchange='this.form.submit();' id='".$tipo_noticias['tipo_noticia']."' name='tipo_noticia[]' value='".$tipo_noticias['tipo_noticia']."' class='oculto'>
                 <label class='lab' for='".$tipo_noticias['tipo_noticia']."'><b>".$tipo_noticias['tipo_noticia']."</b></label>
              </div>";
            }
        }
    }
    mysqli_close($db);
}
      echo "<button type='submit'>Buscar</button>
      </form>";
