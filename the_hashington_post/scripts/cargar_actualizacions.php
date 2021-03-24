<?php
$max_act_pax=5;
$cont_act=0;
$end=false;
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
if (isset($_GET["so"])) {
  $so = $_GET["so"];
}
if (isset($_GET["act"])) {
  if ($_GET["act"] > 5) {
      $max_act_pax = $_GET["act"];
  }
}

$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta_contar = "SELECT COUNT(*) FROM actualizacions_software where sistema_operativo = '".$so."'";
    $res_contar = mysqli_query($db, $consulta_contar);
    $total_actualizacions = mysqli_fetch_row($res_contar);
    $consulta = "SELECT * FROM actualizacions_software where sistema_operativo = '".$so."'  order by data_saida desc limit 0, $max_act_pax";
    $res_consulta = mysqli_query($db, $consulta);
    if ($res_consulta) {
        while ($actualizacions_software = mysqli_fetch_assoc($res_consulta)) {
        echo "<a name='".$cont_act."'></a>";
        echo "<h2>".$actualizacions_software['titulo']."</h2>
              <p class='art_contido'>".$actualizacions_software['contido']."</p>
              <p class='meta_updates'>".$actualizacions_software['num_version']." | ".$actualizacions_software['data_saida']."</p>
              <br/>
              <hr/>";
              $cont_act++;
              if ($cont_act == $total_actualizacions[0]) {
                $end=true;
              }
        }
    }
mysqli_close($db);
}

if ($end == false) {
  $max_act_pax = $max_act_pax+5;
  echo "<div class='vermais'>
          <a href='$so.php?so=$so&act=$max_act_pax#$cont_act'><i class='fa fa-chevron-circle-down'></i></a>
          <a name='fin'></a>
          </div>";
}
?>
