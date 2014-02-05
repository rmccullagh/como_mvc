<?php
use \Uri;
class TestController extends BaseController {

    public function __construct() {

        parent::__construct();	

    }
    public function index() {
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
