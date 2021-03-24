<?php
include 'header.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI'];
include 'aside.php';
$datos_conexion = parse_ini_file('../scripts/ini/datos_conexion.ini',true);
?>
    <form class='busqueda2' method='get' action=''>
        <div class='filtro'>
<?php
if (!isset($_GET['selcp'])) { $selcp = 'concepto';
} else {
  $selcp = $_GET['selcp'];
  }

if (!isset($_GET["paxina"])) {
  $paxina="1";
} else {
  $paxina = $_GET["paxina"];
  }

if ($selcp == 'procedemento') {
  //estes input radio funcionan ao mesmo tempo como submits do formulario empregando unha funcion de javascript: 'this.form.submit();'
    echo "   <input onchange='this.form.submit();' type='radio' id='proc' name='selcp' value='procedemento' class='oculto' checked='checked'>";
  } else {
    echo "   <input onchange='this.form.submit();' type='radio' id='proc' name='selcp' value='procedemento' class='oculto'>";
    }
echo "       <label class='lab2' for='proc'><b>Procedementos</b></label>
        </div>
        <div class='filtro'>";

if ($selcp == 'concepto') {
    echo "   <input onchange='this.form.submit();' type='radio' id='proc' name='selcp' value='concepto' class='oculto' checked='checked'>";
  } else {
    echo "   <input onchange='this.form.submit();' type='radio' id='conc' name='selcp' value='concepto' class='oculto'>";
    }
  echo "     <label class='lab2' for='conc'><b>Conceptos</b></label>
        </div>
    </form>";

//cargar a cabeceira correcta de cada páxina
switch ($selcp) {
    case "concepto":
        echo "
        <div class='cabeceira_c'><h2 class='header_conceptos'>Conceptos</h2>
            <p>Extremity direction existence as dashwoods do up. Securing marianne led welcomed offended but offering six raptures. Conveying concluded newspaper rapturous oh at. Two indeed suffer saw beyond far former mrs remain. Occasional continuing possession we insensible an sentiments as is. Law but reasonably motionless principles she. Has six worse downs far blush rooms above stood.
            <br/>
                Much evil soon high in hope do view. Out may few northward believing attempted. Yet timed being songs marry one defer men our. Although finished blessing do of. Consider speaking me prospect whatever if. Ten nearer rather hunted six parish indeed number. Allowance repulsive sex may contained can set suspected abilities cordially. Do part am he high rest th
                r men our. Although finished blessing do of. Consider speaking me prospect whatever if. Ten nearer rather hunted six parish indeed number. Allowance repulsive sex may contained can set suspected abilities cordially. Do part am he high rest th
            </p>
       </div>";
       if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
           echo "
               <div class='botonsedicion'>
                    <div class='botonconf_a2'>
                       <b><a href='../scripts/editar_conc_proc.php?selcp=".$selcp."' class='botonconf_b'>Engadir</a></b>
                    </div>
                </div>";
        }
        break;
    case "procedemento":
        echo "
        <div class='cabeceira_c'><h2 class='header_conceptos'>Procedementos</h2>
            <p>Extremity direction existence as dashwoods do up. Securing marianne led welcomed offended but offering six raptures. Conveying concluded newspaper rapturous oh at. Two indeed suffer saw beyond far former mrs remain. Occasional continuing possession we insensible an sentiments as is. Law but reasonably motionless principles she. Has six worse downs far blush rooms above stood.
               <br/>
               Much evil soon high in hope do view. Out may few northward believing attempted. Yet timed being songs marry one defer men our. Although finished blessing do of. Consider speaking me prospect whatever if. Ten nearer rather hunted six parish indeed number. Allowance repulsive sex may contained can set suspected abilities cordially. Do part am he high rest th
                r men our. Although finished blessing do of. Consider speaking me prospect whatever if. Ten nearer rather hunted six parish indeed number. Allowance repulsive sex may contained can set suspected abilities cordially. Do part am he high rest th
              </p>
         </div>";
         if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
          echo "
             <div class='botonsedicion'>
                  <div class='botonconf_a2'>
                     <b><a href='../scripts/editar_conc_proc.php?selcp=".$selcp."' class='botonconf_b'>Engadir</a></b>
                  </div>
              </div>";
         }
        break;
}
?>

             <section class='sec_principal2'>
<?php
$cont=0;
$end=false;

$numpax = ($paxina -1)*10;
$i=$numpax;
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta_contar = "SELECT COUNT(*) FROM concep_proced where categoría = '".$selcp."'";
    $res_contar = mysqli_query($db, $consulta_contar);
    $total_concep_proced = mysqli_fetch_row($res_contar);
    $consulta = "SELECT * FROM concep_proced where categoría = '".$selcp."' order by id_artigo limit $i, 8";
    $res_consulta = mysqli_query($db, $consulta);
    if ($res_consulta) {
        //echo "total=".$total_concep_proced[0]."<br/>";
        while ($concep_proced = mysqli_fetch_assoc($res_consulta)) {
            echo "
                  <div class='software'><a href='conc_prod.php?selcp=".$selcp."&id_artigo=".$concep_proced['id_artigo']."' class='linksw'>
                      <div class='cont_imaxe'>
                          <img src='".$concep_proced['imaxe_preview']."' class='imaxe'>
                          <div class='textoimx'>
                              <p class='pefoto'><b>".$concep_proced['titulo']."</b></p>
                              <p class>".$concep_proced['definicion_preview']."</p>
                          </div>
                      </div></a>
                  </div>";
             $i++;
            if ($i == $total_concep_proced[0]) {
                $end=true;
            }
        }
    }
    mysqli_close($db);
}



if ($end == false) {
echo "<div class='cambiar'>";
echo "   <a href='conceptos_e_procedementos.php?selcp=$selcp&paxina=".++$paxina."'><i class='fa fa-angle-right'></i></a>";
echo "</div>";
}

if (isset($_GET['paxina'])) {
  $paxina = $_GET["paxina"];
  if ($paxina > 1) {
  echo "<div class='cambiar'>";
  echo "   <a href='conceptos_e_procedementos.php?selcp=$selcp&paxina=".--$paxina."'><i class='fa fa-angle-left'></i></a>";
  echo "</div>";
  }
}
?>
      </section>
<?php
     include 'footer.php';
?>
