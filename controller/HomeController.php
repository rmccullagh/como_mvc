<?php

use \Uri;
use \Lib\String\Sanitizer;
use \Lib\Cache\MemcacheDriver;
use \Lib\Cache\MemcacheDriverException;
use \Lib\Input\Input;
use \Model\Home;
use \Lib\Response\Responder;

class HomeController extends BaseController 
{

    public  function __construct() 
    {

        parent::__construct();	

    }
    public function index() 
    {       
        $a = new Home();
        $a->getProducts();

        echo "sanitize::rXssClean(): " . Sanitizer::xssClean('<script>aa</script>');
        echo "<br />";
        echo "Input::get(): " . Input::get('q', Input::FILTER_ARRAY);
        echo "<br />";
        echo "Input::get(Input::NO_FILTER_ARRAY): ";
        
        try { 
            $cache = new MemcacheDriver(array(
                "host" => "127.0.0.1", 
                "port" => "11211"
            ));
        } catch(MemcacheDriverException $e) {          
            echo $e->getXdebugMessage();
        }


        $this->view->set(array(
            "title" => "Welcome to Como MVC", 
        ))->render();
    }
    public function a()
    {
        $responder = new Responder(array(
            "title" => "Como MVC Framework for PHP"
        ));

        $responder->setPath('Common')->setPaglet('Header');
        $responder->setPath('Home')->setPaglet('Body');
        $responder->setPath('Common')->setPaglet('Footer');    
        $responder->renderOutput();

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
    public function redirect() {
        Input::redirect("http://google.com");
    }
    public static function static_fn() {
        echo __METHOD__;
    }
}
