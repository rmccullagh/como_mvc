<?php 
namespace Lib\Input;

class Input {

    const FILTER_ARRAY    = 0;
    const NO_FILTER_ARRAY = 1; 

    public static function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    public static function user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public static function is_ajax() 
    {
        return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');	
    }
    public static function post($key, $filter_array = 0, $default = NULL)
    {
        $item = isset($_POST[$key]) ? $_POST[$key] : NULL;
        if($filter_array === 0) {
            if(is_array($item))
                return NULL;
        }
        return isset($_POST[$key]) ? self::clean($_POST[$key]) : $default;        
    }
    public static function get($key, $filter_array = 0, $default = NULL)
    {
        if(filter_has_var(INPUT_GET, $key)) {
            $item = filter_var($_GET['q'], FILTER_SANITIZE_STRING);
            var_dump($item);
        }
        
        $item = isset($_GET[$key]) ? $_GET[$key] : NULL;
        if($filter_array === 0) {
            if(is_array($item))
                return NULL;
        }
        return isset($_GET[$key]) ? self::clean($_GET[$key]) : $default;        
    }
    public static function clean($type)
    {
        if(is_array($type)) {
            foreach($type as $key => $value){	
                $type[$key] = self::clean($value);
            }
            return $type;
        } else {
            $string = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
            return $string;
        }
    }
    public static function redirect($location)
    {
        if(!headers_sent()) {
            header('HTTP/1.1 301 Moved Permanently');
            header("Location: $location");
            exit;
        }
    }
}
