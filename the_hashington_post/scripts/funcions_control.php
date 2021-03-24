<?php

function limpar_datos($datos) {
   $datos = trim($datos);
   $datos = stripslashes($datos);
   $datos = htmlspecialchars($datos);
   return $datos;
}

function validaVbleForm($var) {
        if (!isset($_REQUEST[$var])) {
            $tmp = "";
        } elseif (!is_array($_REQUEST[$var])) {
            $tmp = trim(htmlspecialchars($_REQUEST[$var], ENT_QUOTES, "UTF-8"));
        } else {
            $tmp = $_REQUEST[$var];
            array_walk_recursive($tmp, function (&$valor) {
                $valor = trim(htmlspecialchars($valor, ENT_QUOTES, "UTF-8"));
            });
        }
        return $tmp;
    }

?>
