<?php
include 'header.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI'];
include 'aside.php';
if (isset($_SESSION['user_type']) && (($_SESSION['user_type'] == 'administrador') OR ($_SESSION['user_type'] == 'editor'))) {
    echo "
              <div class='botonconf_a'>
                  <b><a href='../scripts/editar_actualizacions.php?so=blackarch&accion=editar' class='botonconf_b'>Editar</a></b>
              </div>
              <div class='botonconf_a'>
                  <b><a href='../scripts/editar_actualizacions.php?so=blackarch&accion=insertar' class='botonconf_b'>Insertar</a></b>
              </div>";
}
?>
              <section>
                 <article class="art_completo2">
                     <h2 class="header_software"><image src="../imaxes/archfondo.png" class="tituloimx"></image></h2>
                     <br/>
                     <p class="art_contido">Extremity direction existence as dashwoods do up. Securing marianne led welcomed offended but offering six raptures. Conveying concluded newspaper rapturous oh at. Two indeed suffer saw beyond far former mrs remain. Occasional continuing possession we insensible an sentiments as is. Law but reasonably motionless principles she. Has six worse downs far blush rooms above stood.
                     <br/>
                     Much evil soon high in hope do view. Out may few northward believing attempted. Yet timed being songs marry one defer men our. Although finished blessing do of. Consider speaking me prospect whatever if. Ten nearer rather hunted six parish indeed number. Allowance repulsive sex may contained can set suspected abilities cordially. Do part am he high rest that. So fruit to ready it being views match.
                     <br/>
                     View fine me gone this name an rank. Compact greater and demands mrs the parlors. Park be fine easy am size away. Him and fine bred knew. At of hardly sister favour. As society explain country raising weather of. Sentiments nor everything off out uncommonly partiality bed.

                     So by colonel hearted ferrars. Draw from upon here gone add one. He in sportsman household otherwise it perceived instantly. Is inquiry no he several excited am. Called though excuse length ye needed it he having. Whatever throwing we on resolved entrance together graceful. Mrs assured add private married removed believe did she...</p>
                     <br/>
                      <p class="art_contido">Extremity direction existence as dashwoods do up. Securing marianne led welcomed offended but offering six raptures. Conveying concluded newspaper rapturous oh at. Two indeed suffer saw beyond far former mrs remain. Occasional continuing possession we insensible an sentiments as is. Law but reasonably motionless principles she. Has six worse downs far blush rooms above stood.
                     <br/>
                     Much evil soon high in hope do view. Out may few northward believing attempted. Yet timed being songs marry one defer men our. Although finished blessing do of. Consider speaking me prospect whatever if. Ten nearer rather hunted six parish indeed number. Allowance repulsive sex may contained can set suspected abilities cordially. Do part am he high rest that. So fruit to ready it being views match.
                     <br/>
                     View fine me gone this name an rank. Compact greater and demands mrs the parlors. Park be fine easy am size away. Him and fine bred knew. At of hardly sister favour. As society explain country raising weather of. Sentiments nor everything off out uncommonly partiality bed.

                     So by colonel hearted ferrars. Draw from upon here gone add one. He in sportsman household otherwise it perceived instantly. Is inquiry no he several excited am. Called though excuse length ye needed it he having. Whatever throwing we on resolved entrance together graceful. Mrs assured add private married removed believe did she...</p>
                     <h2 class="header_updates">Actualizaci&oacute;ns</h2><hr/>
<?php
                      #estes includes dependen do que se seleccione en editar_software.php
                     include '../scripts/cargar_actualizacions.php';
?>
                      <div class="atras">
                          <a href="software.php"><i class="fa fa-angle-double-left"></i></a>
                     </div>
                </article>
            </section>

<?php
     include 'footer.php';
?>
