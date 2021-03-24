<?php
    include 'headeradm.php';
?>  
    <body>
      <main>
      <div class="login">
          <h1>Restablecer contrasinal</h1>
          <form method="post">
              <input type="text" name="u" placeholder="Usuario" required="required"/>
              <input type="email" name="mail" placeholder="Correo electr&oacute;nico" required="required"/>  
              <button type="submit" class="btnlogin">Enviar c&oacute;digo</button>
              <p><br/><br/></p>
          </form>
          <form>
              <i>Introduce o c&oacute;digo que recibiches no teu correo electr&oacute;nico.</i>
              <input type="codigo" name="cdg" placeholder="C&oacute;digo" required="required"/>
              <input type="password" name="pwd" placeholder="Novo contrasinal" required="required"/>
              <input type="password" name="pwd2" placeholder="Repita o novo contrasinal" required="required"/>
              <button type="submit" class="btnlogin">Cambiar contrasinal</button>
          </form>
      </div>
<?php
    include 'footer.php';
?>