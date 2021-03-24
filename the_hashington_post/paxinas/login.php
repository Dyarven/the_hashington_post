<?php
    include 'headeradm.php';
    include '../scripts/login_control.php';
echo "
    <body>
      <main>
      <div class='login'>
          <h1>Iniciar sesi&oacute;n</h1>
          <form method='POST' action=''>";
?>
              <input type='text' name='usuario' value='' placeholder='Usuario'required='required'/>
              <?php echo $usuario_err ?>
              <input type='password' name='contrasinal' value='' placeholder="Contrasinal" required='required'/>
              <?php echo $contrasinal_err ?>
              <button type='submit' value='login' class='btnlogin'>Entrar</button>
          </form>
          <p><br/><br/>
            <a href='rstpasswd.php'>Olvidei o meu contrasinal</a>
          </p>
      </div>
<?php
  include 'footer.php'
?>
