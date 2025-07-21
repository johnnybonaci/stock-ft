<?php

namespace App\Helper;

/**
 * Designed in order to fullfil debugging necessities.
 */
final class Debbug
{
    /**
     * Dump and die.
     * Recibe un dato y lo imprime en pantalla con un var_dump y luego mata el proceso.
     *
     * @param bool $die Indicates whether to exit execution or not
     * @param mixed $var Var to expose trought dumping
     * @param mixed $msj Debugging message
     *
     * @return void
     */
    public static function dump($var, $msj = 'Debbug', $die = true): void
    {
        echo '<pre style="
        background-color: #000;
        color: #fff;
        padding: 10px;
        margin: 10px;
        font-size: 12px;
        font-family: monospace;">';

        echo '<strong>' . $msj . '</strong><br><br>';
        var_dump($var);
        echo '</pre>';
        if ($die) {
            exit;
        }
    }
}
