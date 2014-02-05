<?php
use \Uri;
use \Lib\String\Sanitizer;
use \Lib\Cache\MemcacheDriver;
use \Lib\Cache\MemcacheDriverException;

class HomeController extends BaseController {

    public  function __construct() {

        parent::__construct();	

    }
    public function index() {
        try { 
            $cache = new MemcacheDriver(array(
                "host" => "127.0.0.1", 
                "port" => "11211"
            ));
            /*
            var_dump($cache->get('aaaa'));
            $cache->set('aaaa', array("test"), 10);
            var_dump($cache->get('aaaa'));
            $cache->flush();
            */

        } catch(MemcacheDriverException $e) {
            
            echo $e->getXdebugMessage();
        }

        $this->view->set(array(
            "title" => "Welcome to Como MVC", 
        ))->render();
    }
    public function test($arg1, $arg2) {
        echo $arg1 . "<br>";
        echo $arg2 . "<br>";
    }
    private function hello() {
        echo __METHOD__;
    }
    protected function world() {
        echo __METHOD__;
    }
    public function test1() {
        echo __METHOD__;
    }
    public static function static_fn() {
        echo __METHOD__;
    }
}
