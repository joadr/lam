<?php namespace LaravelLam\Lam\Output; 
/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:29
 */
class TerminalOutput {

    public static function say($string, $style = null) {

        if ($style) {
            $string = TerminalColor::set($string, $style);
        }

        echo $string . "\n";
    }

}