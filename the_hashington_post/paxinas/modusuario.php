<?php
include 'headeradm.php';
include '../scripts/chpasswd.php';
?>
    <body>
        <main>
            <div class="login">
                <h1>Modificar contrasinal</h1>
                <form method="post">
                    <div><?php echo $err;?></div>
                    <input type="password" name="contrasinal_vello" placeholder="Contrasinal actual" required="required"/>
                    <input type="password" name="contrasinal_novo" minlength='7' placeholder="Novo contrasinal" required="required"/>
                    <div><?php echo $noteq ;?></div>
                    <input type="password" name="repetir_contrasinal" minlength='7' placeholder="Repita o novo contrasinal" required="required"/>
                    <button type="submit" name="enviar" value="" class="btnlogin">Cambiar</button>
                </form>
                <div><?php echo $exito;?></div><div><?php echo $iguais;?></div>
                <br/><br/><br/>
                <button type="button" class="btnlogin"><a href="../inicio_admin.php">Volver ao sitio</a></button>
            </div>
<?php
        include 'footer.php';
?>
