<?php
namespace Lib\String;

class Sanitizer {
    public static function xssClean($string) {
        if(is_string($string)) {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }
    }
}
