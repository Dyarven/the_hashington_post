<?php
session_start();
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI'];
include './paxinas/header_inicio_admin.php';
include './paxinas/aside_inicio.php';
include './scripts/cargar_tipo_noticias.php';
include './scripts/cargar_noticias.php';
include './paxinas/footer.php';
?>
