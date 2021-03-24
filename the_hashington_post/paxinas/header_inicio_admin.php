<?php
echo "
<!DOCTYPE html>
<html lang='gl'>
    <head>
        <meta charset='UTF-8' name='author' content='David Castro 2ºASIR'>
        <link rel='stylesheet' type='text/css' href='./css/estilos.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
        <link rel='icon' type='image/png' href='./imaxes/favicon.ico'>
        <link href='https://fonts.googleapis.com/css?family=Libre Baskerville' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Questrial' rel='stylesheet'>
        <title>The Hashington Post</title>
    </head>
    <body>
        <div class='sec_botons'>";
if (isset($_SESSION['username'])) {
        echo "
            <ul>
                <li class='login'>
                    <p class='header_userlogin'>".$_SESSION['username']."</p>
                </li>";
        echo "
                <li class='login'><a href='./paxinas/modusuario.php'>
                    <i class='fa fa-key fa fa-2x'></i>
                    </a>
                </li>";
}
if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 'administrador')) {
        echo "
                <li class='login'><a href='./scripts/paneladmin.php'>
                    <i class='fa fa-cog fa fa-2x'></i>
                    </a>
                </li>";
}
        echo "
                <li class='login'><a href='./paxinas/login.php'>
                    <i class='fa fa-power-off fa fa-2x'></i>
                    </a>
                </li>
            </ul>
        </div>";

echo "
        <header>
            <h1 class='nomepaxina'><a href='inicio_admin.php'>The Hashington Post</a></h1>
        </header>
        <nav class='navegacion'>
            <ul class='menu'>
                <li><a href='inicio_admin.php'>Novas</a>
                </li>
                <li><a href='./paxinas/software.php'>Software</a>
                    <ul class='submenu'>
                        <li><a href='./paxinas/kali.php?so=kali'>Kali Linux</a></li>
                        <li><a href='./paxinas/blackarch.php?so=blackarch'>BlackArch Linux</a></li>
                    </ul>
                </li>
                <li><a href='./paxinas/conceptos_e_procedementos.php'>Conceptos e procedementos</a>
                </li>
                <li><a href='./paxinas/about.php'>Sobre n&oacute;s</a></li>
            </ul>
         </nav>";
?>
