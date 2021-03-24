<?php
include 'header.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI'];
include 'aside.php';
?>
             <section class="cabeceira_c2">
                 <h2 class='header_noticias2'>A túa páxina de noticias de ciberseguridade</h2>
                <article class="article_info">
                  <p>Nun mundo sobrecargado de información e estímulos, no que permanecemos conectados á rede case constantemente, é preciso saber a qu&eacute; perigos nos enfrontamos, que plataformas que empregamos habitualmente son atacadas poñendo en perigo as nosas credenciais e como reaccionar cando isto acontece.
                    <br/>
                    Dende o Hashington Post tomámonos moi en serio esta labor de comunicación, investigando diariamente os casos de malware, filtraci&oacute;ns de datos, ciberataques, novas vulnerabilidades e moito m&aacute;is.
                    <br/><br/>
                    Grazas ao noso equipo de colaboradores, cada semana traemos ademais contido formativo relacionado coa ciberseguridade, analizamos os mellores sistemas operativos para pentesting e ethical hacking dispo&ntilde;ibles no mercado e cont&aacute;mosche que podes facer para estar m&aacute;is seguro na rede.
                    <br/><br/>
                    Encontrar&aacute;s documentaci&oacute;n variada e recomendaci&oacute;ns de software tanto open source como de licencia privada.
                    <br/>

                    O Hashington post, medra d&iacute;a a d&iacute;a expandindo o contido ofrecido e mellorando a súa calidade grazas ao apoio dos lectores. <br/> <br/>

                     O noso bolet&iacute;n, ademais de ser completamente gratis, non s&oacute; che achegar&aacute; as &uacute;ltimas novas, tam&eacute;n te informar&aacute; se as&iacute; o desexas das notas dos parches dos dous sistemas operativos que recomendamos: BlackArch Linux e Kali Linux, e, se o desexas, das diferentes ofertas de software relacionado e promoci&oacute;ns que fagamos en colaboraci&oacute;n coas marcas.

                    </p>
                </article>
            </section>
            <section class="sec_boletin">
            <form method="post" action="por definir">
               <div class="boletin">
                   <h2>Suscr&iacute;bete para estar ao tanto das novas de &uacute;ltima hora.</h2>
                   <p class="advertencia"><em>Servizo completamente gratu&iacute;to.</em></p>
               </div>
               <div class="boletin">
                   <input type="text" placeholder="Nome" name="nome" required>
                   <input type="email" placeholder="Correo Electr&oacute;nico" name="correo" required>
                   <input type="checkbox" checked="checked" name="suscribirse"> Boletín do Hashington Post
               </div>
               <div class="boletin">
                   <input type="submit" value="Suscribirse">
               </div>
            </form>
        </section>

<?php
     include 'footer.php';
?>
