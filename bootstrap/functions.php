<?php
function isDev() {
    return isset($_SERVER['COMO_ENV']) AND $_SERVER['COMO_ENV'] == 'DEV';
}
