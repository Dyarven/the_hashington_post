<?php
include 'header.php';
$_SESSION['pax_prev'] = $_SERVER['REQUEST_URI'];
include 'aside.php';

?>  <section class=sec_soft>
            <div class="todopagb">
<?php
             include 'previewkali.php';
             include 'previewblackarch.php';
?>
        </div>
    </section>

<?php
     include 'footer.php';
?>
