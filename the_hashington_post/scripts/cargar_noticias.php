<?php
if (!isset($_GET["paxina"])) { $paxina="1";
} else { $paxina = $_GET["paxina"]; }
$numpax = ($paxina -1)*10;
$end = false;
$i= $numpax;
$url="";
$filtrado = [];
$concat_consulta="";
$sen_resultados = FALSE; //para agochar os botóns de adiante e atrás
$datos_conexion = parse_ini_file('ini/datos_conexion.ini',true);
$db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
if ($db) {
    $consulta = "select count(id_artigo) from noticias";
    $res_consulta = mysqli_query($db, $consulta);
    $total_noticias = mysqli_fetch_row($res_consulta); //DEVOLVE UN ARRAY
    mysqli_close($db);
}

// Se non hai checkbox marcados e non se está realizando unha busca
if (!isset($_GET['tipo_noticia']) && (!isset($_GET['search']) OR empty($_GET['search']))) {
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta = "SELECT n.id_artigo, n.titulo, n.resumo, u.nome_completo as autor, n.data_publicacion, tn.tipo_noticia as categoría from noticias n
        inner join usuarios u on n.id_usuario = u.id_usuario
        inner join noticias_tipo_noticia ntn on ntn.id_artigo = n.id_artigo
        inner join tipo_noticia tn on tn.id_tipo_noticia = ntn.id_tipo_noticia
        order by n.data_publicacion desc limit $i, 10";
        $res_consulta = mysqli_query($db, $consulta);

        if ($res_consulta) { //se mysqli_query devolve true
            echo "<section class='sec_principal'>";
            while ($noticias = mysqli_fetch_assoc($res_consulta)) { // mentras haxa noticias
                echo "    <article class='art_principal'><a href='./paxinas/noticia.php?noticia=".$noticias['id_artigo']."' class='a_articulo'>";
                echo "    <h2 class='header_noticias'>".$noticias['titulo']."</h2>";
                echo "    <br/>";
                echo "    <p class='art_contido'>".$noticias['resumo']."</p>";
                echo "    <p class='meta_noticia'>".$noticias['categoría']." | ".$noticias['data_publicacion']." | ".$noticias['autor']."</p>";
                echo "    </a>";
                echo "    </article>";
                $i++;
                if ($i == $total_noticias[0]) {// [0] porque fetch row devolve un array) {
                      $end = true;
                }
            }
            if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
                echo "
                <div class='botonsedicion'>
                   <div class='botonconf_a'>
                      <b><a href='./scripts/editar_noticia.php' class='botonconf_b'>Engadir nova</a></b>
                   </div>
                </div>";
            }
            if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador'))) {
                echo "
                <div class='botonsedicion2'>
                   <div class='botonconf_a'>
                      <b><a href='./scripts/editar_tipo_noticias.php' class='botonconf_b'>Categorías</a></b>
                   </div>
                </div>";
            }
        }
    }
    mysqli_close($db);
// Se non hai checkbox marcados e se está realizando unha busca
} elseif (!isset($_GET['tipo_noticia']) && isset($_GET['search']) && !empty($_GET['search'])) {
    $i=$numpax;
    $busca = $_GET['search'];
    $busca = htmlspecialchars($busca);
    $busca = mb_strtolower($busca);
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        // ordena o contido mostrado según o número de coincidencias
        $consulta = "SELECT n.id_artigo, n.titulo, n.resumo, u.nome_completo as autor, n.data_publicacion, tn.tipo_noticia as categoría from noticias n
        inner join usuarios u on n.id_usuario = u.id_usuario
        inner join noticias_tipo_noticia ntn on ntn.id_artigo = n.id_artigo
        inner join tipo_noticia tn on tn.id_tipo_noticia = ntn.id_tipo_noticia
        where LOWER(n.titulo) LIKE '%".$busca."%' OR LOWER(n.resumo) LIKE '%".$busca."%'
        order by case
            when (n.titulo LIKE '%".$busca."%' AND n.resumo LIKE '%".$busca."%') then 1  -- se coinciden ambos campos amosa primeiro esa
            when n.titulo LIKE '%".$busca."%' then 2
            when n.resumo LIKE '%".$busca."%' then 3
        end
        limit $i, 10";
        $consulta_contar = "SELECT count(n.id_artigo) from (noticias n inner join usuarios u on n.id_usuario = u.id_usuario)
        where LOWER(n.titulo) LIKE '%".$busca."%' OR LOWER(n.resumo) LIKE '%".$busca."%'";
        $res_consulta = mysqli_query($db, $consulta);
        $res_consulta2 = mysqli_query($db, $consulta);
        $res_contar = mysqli_query($db, $consulta_contar);
        $num_valores = mysqli_fetch_row($res_contar);
        // print_r($res_consulta2);
        if ($res_consulta) { //se mysqli_query devolve true
            $total_noticias = mysqli_fetch_row($res_consulta2); // hai que usar outra consulta para xerar a variable $total_noticias, se non comeza a mostrar pola segunda fila da táboa
            if ($total_noticias >= 1) {
                echo "<section class='sec_principal'>";
                echo "    <p class='header_busca'>Amosando resultados para '".$busca."'</p>";
                while ($noticias = mysqli_fetch_assoc($res_consulta)) {
                     // mentras haxa noticias
                    echo "    <article class='art_principal'><a href='./paxinas/noticia.php?noticia=".$noticias['id_artigo']."' class='a_articulo'>";
                    echo "    <h2 class='header_noticias'>".$noticias['titulo']."</h2>";
                    echo "    <br/>";
                    echo "    <p class='art_contido'>".$noticias['resumo']."</p>";
                    echo "    <p class='meta_noticia'>".$noticias['categoría']." | ".$noticias['data_publicacion']." | ".$noticias['autor']."</p>";
                    echo "    </a>";
                    echo "    </article>";
                    $i++;
                    if ($i == $num_valores[0]) {
                        $end = true;
                    }
                }
                if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
                    echo "
                    <div class='botonsedicion'>
                       <div class='botonconf_a'>
                          <b><a href='./scripts/editar_noticia.php' class='botonconf_b'>Engadir nova</a></b>
                       </div>
                    </div>";
                }
                if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador'))) {
                    echo "
                    <div class='botonsedicion2'>
                       <div class='botonconf_a'>
                          <b><a href='./scripts/editar_tipo_noticias.php' class='botonconf_b'>Categorías</a></b>
                       </div>
                    </div>";
                }
            } else {
                $sen_resultados = true;
                echo "<section class='sec_principalerr'>";
                echo "    <article class='header_erro'>Non se atoparon resultados</article>";
                echo "    <article class='header_erro2'><a class='linksw2' href='inicio_admin.php'>Preme aquí para voltar á páxina de inicio</a></article>";
                echo "</section class='sec_principalerr'>";
            }
        mysqli_close($db);
        }
    }
// Se hai checkbox marcados e non se está realizando unha busca
} elseif (isset($_GET['tipo_noticia']) && (!isset($_GET['search']) OR empty($_GET['search']))) {
  $filtrarnoticias = [];
  $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
  $consulta = "select tipo_noticia from tipo_noticia";
  $res_consulta = mysqli_query($db, $consulta);
  if ($res_consulta) {
      while ($tipo_noticia = mysqli_fetch_assoc($res_consulta)) {
          if (${'buscar'.$tipo_noticia['tipo_noticia']}) {
              $filtrarnoticias[$tipo_noticia['tipo_noticia']] = ' tipo_noticia.tipo_noticia =\''.$tipo_noticia['tipo_noticia'].'\''; // $filtrarnoticias[$tipo_noticia['tipo_noticia']] -> está asignando a un array como clave de outro array
              // Array ( [Vulnerabilidades] => tipo_noticia.tipo_noticia ='Vulnerabilidades' )
          }
       }
   }
    mysqli_close($db);

    for ($i=0; $i < count($_GET['tipo_noticia']); $i++) {
            $checkbox = $_GET['tipo_noticia'][$i];
            $url .= "&tipo_noticia[]=$checkbox";
    }
    $j=0;
    $num=count($filtrarnoticias);
    if ($num > 0) {
        foreach ($filtrarnoticias as $valor) {
            if ($j < ($num -1)) {
                $concat_consulta .= $valor.' OR';
                $j++;
            } else {
                $concat_consulta .= $valor;
            }
        }
    } else {
        foreach ($filtrarnoticias as $valor) {
            $concat_consulta = $valor;
        }
    }
    $i=$numpax;
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta = "SELECT n.id_artigo, n.titulo, n.resumo, u.nome_completo as autor, n.data_publicacion, tipo_noticia.tipo_noticia as categoría from tipo_noticia
        inner join noticias_tipo_noticia on (tipo_noticia.id_tipo_noticia = noticias_tipo_noticia.id_tipo_noticia)
        inner join noticias n on (n.id_artigo = noticias_tipo_noticia.id_artigo)
        inner join usuarios u on n.id_usuario = u.id_usuario
        where ".$concat_consulta." order by data_publicacion desc limit $i, 10";
        $consulta_contar = "SELECT count(n.id_artigo) from tipo_noticia
        inner join noticias_tipo_noticia on (tipo_noticia.id_tipo_noticia = noticias_tipo_noticia.id_tipo_noticia)
        inner join noticias n on (n.id_artigo = noticias_tipo_noticia.id_artigo)
        where ".$concat_consulta."";
        $res_consulta = mysqli_query($db, $consulta);
        $res_contar = mysqli_query($db, $consulta_contar);
        $num_valores =  mysqli_fetch_row($res_contar);
        if ($num_valores[0] > 0) {
        echo "<section class='sec_principal'>";
            while ($filtrado = mysqli_fetch_assoc($res_consulta)) {
                    echo "<article class='art_principal'><a href='./paxinas/noticia.php?noticia=".$filtrado['id_artigo']."' class='a_articulo'>";
                    echo "<h2 class='header_noticias'>".$filtrado['titulo']."</h2>";
                    echo "<br/>";
                    echo "<p class='art_contido'>".$filtrado['resumo']."</p>";
                    echo "<p class='meta_noticia'>".$filtrado['categoría']." | ".$filtrado['data_publicacion']." | ".$filtrado['autor']."</p>";
                    echo "</a>";
                    echo "</article>";
                    $i++;
                    if ($i == $num_valores[0]) {
                          $end = true;
                    }
             }
             if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
                 echo "
                 <div class='botonsedicion'>
                    <div class='botonconf_a'>
                       <b><a href='./scripts/editar_noticia.php' class='botonconf_b'>Engadir nova</a></b>
                    </div>
                 </div>";
             }
             if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador'))) {
                 echo "
                 <div class='botonsedicion2'>
                    <div class='botonconf_a'>
                       <b><a href='./scripts/editar_tipo_noticias.php' class='botonconf_b'>Categorías</a></b>
                    </div>
                 </div>";
             }
         } else {
             $sen_resultados = true;
             echo "<section class='sec_principalerr'>";
             echo "    <article class='header_erro'>Non se atoparon resultados</article>";
             echo "    <article class='header_erro2'><a class='linksw2' href='inicio_admin.php'>Preme aquí para voltar á páxina de inicio</a></article>";
             echo "</section class='sec_principalerr'>";
         }
    }
    mysqli_close($db);
// Se hai checkbox marcados e se está realizando unha busca
} else {
    $filtrarnoticias = [];
      $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
      $consulta0 = "select tipo_noticia from tipo_noticia";
      $res_consulta0 = mysqli_query($db, $consulta0);
      if ($res_consulta0) {
          while ($tipo_noticia = mysqli_fetch_assoc($res_consulta0)) {
              if (${'buscar'.$tipo_noticia['tipo_noticia']}) {
                  $filtrarnoticias[$tipo_noticia['tipo_noticia']] = ' tipo_noticia.tipo_noticia =\''.$tipo_noticia['tipo_noticia'].'\''; // $filtrarnoticias[$tipo_noticia['tipo_noticia']] -> está asignando a un array como clave de outro array
                  // Array ( [Vulnerabilidades] => tipo_noticia.tipo_noticia ='Vulnerabilidades' )
              }
           }
       }
        mysqli_close($db);

    for ($i=0; $i < count($_GET['tipo_noticia']); $i++) {
            $checkbox = $_GET['tipo_noticia'][$i];
            $url .= "&tipo_noticia[]=$checkbox";
    }
    $j=0;
    $num=count($filtrarnoticias);
    if ($num > 0) {
        foreach ($filtrarnoticias as $valor) {
            if ($j < ($num -1)) {
                $concat_consulta .= $valor.' OR';
                $j++;
            } else {
                $concat_consulta .= $valor;
            }
        }
    } else {
        foreach ($filtrarnoticias as $valor) {
            $concat_consulta = $valor;
        }
    }
    $i=$numpax;
    $busca = $_GET['search'];
    $busca = htmlspecialchars($busca);
    $busca = mb_strtolower($busca);
    $db = mysqli_connect($datos_conexion["conexion"]["host"], $datos_conexion["conexion"]["user"], $datos_conexion["conexion"]["password"], $datos_conexion["conexion"]["bd"]);
    if ($db) {
        $consulta = "SELECT n.id_artigo, n.titulo, n.resumo, u.nome_completo as autor, n.data_publicacion, tipo_noticia.tipo_noticia as categoría from tipo_noticia
        inner join noticias_tipo_noticia on (tipo_noticia.id_tipo_noticia = noticias_tipo_noticia.id_tipo_noticia)
        inner join noticias n on (n.id_artigo = noticias_tipo_noticia.id_artigo)
        inner join usuarios u on n.id_usuario = u.id_usuario
        where (".$concat_consulta.") AND (LOWER(n.titulo) LIKE '%".$busca."%' OR LOWER(n.resumo) LIKE '%".$busca."%')
        order by case
            when (n.titulo LIKE '%".$busca."%' AND n.resumo LIKE '%".$busca."%') then 1 -- se coinciden ambos campos amosa primeiro esa
            when n.titulo LIKE '%".$busca."%' then 2
            when n.resumo LIKE '%".$busca."%' then 3
        end
        limit $i, 10";
        $res_consulta = mysqli_query($db, $consulta);

        $consulta_contar = "SELECT count(n.id_artigo) AS num_artigos, n.titulo from tipo_noticia inner join noticias_tipo_noticia on (tipo_noticia.id_tipo_noticia = noticias_tipo_noticia.id_tipo_noticia)
        inner join noticias n on (n.id_artigo = noticias_tipo_noticia.id_artigo)
        where (".$concat_consulta.") AND (LOWER(n.titulo) LIKE '%".$busca."%' OR LOWER(n.resumo) LIKE '%".$busca."%')";
        $res_contar = mysqli_query($db, $consulta_contar);
        $num_valores =  mysqli_fetch_row($res_contar);
        $res_consulta2 = mysqli_query($db, $consulta);
        $total_noticias = mysqli_fetch_row($res_consulta2);
        if ($total_noticias >= 1) {
            echo "<section class='sec_principal'>";
            echo "    <p class='header_busca'>Amosando resultados para '".$busca."'</p>";
            while ($filtrado = mysqli_fetch_assoc($res_consulta)) {
                    echo "<article class='art_principal'><a href='./paxinas/noticia.php?noticia=".$filtrado['id_artigo']."' class='a_articulo'>";
                    echo "<h2 class='header_noticias'>".$filtrado['titulo']."</h2>";
                    echo "<br/>";
                    echo "<p class='art_contido'>".$filtrado['resumo']."</p>";
                    echo "<p class='meta_noticia'>".$filtrado['categoría']." | ".$filtrado['data_publicacion']." | ".$filtrado['autor']."</p>";
                    echo "</a>";
                    echo "</article>";
                    $i++;
                    if ($i == $num_valores[0]) {
                          $end = true;
                    }
            }
            if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
                echo "
                <div class='botonsedicion'>
                   <div class='botonconf_a'>
                      <b><a href='./scripts/editar_noticia.php' class='botonconf_b'>Engadir nova</a></b>
                   </div>
                </div>";
            }
            if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador'))) {
                echo "
                <div class='botonsedicion2'>
                   <div class='botonconf_a'>
                      <b><a href='./scripts/editar_tipo_noticias.php' class='botonconf_b'>Categorías</a></b>
                   </div>
                </div>";
            }
        } else {
                $sen_resultados = true;
                echo "<section class='sec_principalerr'>";
                echo "    <article class='header_erro'>Non se atoparon resultados</article>";
                echo "    <article class='header_erro2'><a class='linksw2' href='inicio_admin.php'>Preme aquí para voltar á páxina de inicio</a></article>";
                echo "</section class='sec_principalerr'>";
            }
    mysqli_close($db);
    }
}
//https://localhost/aplicacionweb/inicio_admin.php?search=&tipo_noticia%5B%5D=Ciberataques&tipo_noticia%5B%5D=Malware

//botóns adiante
if (isset($_GET['tipo_noticia'])) {
    if (($end == false) AND ($sen_resultados == false)) {
        echo "<div class='cambiar'>";
        echo "  <a href='inicio_admin.php?&search=$busca$url&paxina=".++$paxina."'><i class='fa fa-angle-right'></i></a>";
    }
    echo "</div>";
} else {
    if (($end == false) AND ($sen_resultados == false)) {
        echo "<div class='cambiar'>";
        echo "  <a href='inicio_admin.php?&search=$busca$url&paxina=".++$paxina."'><i class='fa fa-angle-right'></i></a>";
    }
    echo "</div>";
}
#botóns atrás
if (isset($_GET['tipo_noticia'])) {
    if (isset($_GET['paxina'])) {
        $paxina = $_GET["paxina"];
        if ($paxina > 1) {
            echo "<div class='cambiar'>";
            echo "  <a href='inicio_admin.php?&search=$busca$url&paxina=".--$paxina."'><i class='fa fa-angle-left'></i></a>";
        }
        echo "</div>";
    }
} else {
    if (isset($_GET['paxina'])) {
        $paxina = $_GET["paxina"];
        if ($paxina > 1) {
            echo "<div class='cambiar'>";
            echo "  <a href='inicio_admin.php?&search=$busca$url&paxina=".--$paxina."'><i class='fa fa-angle-left'></i></a>";
        }
        echo "</div>";
    }
}
echo "</section>";

?>
