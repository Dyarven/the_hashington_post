<?php
session_start();
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
        <header>
            <h1 class='nomepaxina'><a href='inicio.php'>The Hashington Post</a></h1>
        </header>
        <nav class='navegacion'>
            <ul class='menu'>
                <li><a href='inicio.php'>Novas</a>
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
