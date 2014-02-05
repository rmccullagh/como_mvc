<?php
class Uri {
    public static function baseUrl($uri = '')
    {
        $protocol   = strtolower(substr($_SERVER["SERVER_PROTOCOL"],  0,  5)) == 'https' ? 'https://' : 'http://';
        $path       = $_SERVER['PHP_SELF'];
        $pathinfo   = pathinfo($path);
        $directory  = $pathinfo['dirname'];
        $directory  = ($directory == "/") ? "" : $directory;
        $host       = $_SERVER['HTTP_HOST'];
        $url        =  $protocol.$host.$directory;

        if($uri != NULL)
            $url .= "/" . $uri;
        return $url;
    }
    public static function currentUrl() {

        return self::baseUrl() . $_SERVER['REQUEST_URI'];
    
    }
}
