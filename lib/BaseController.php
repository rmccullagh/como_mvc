<?php
use \Session;
use \ModelFactory;
use \Input\Input;
use \Config\Xml;
use \View;
abstract class BaseController {
    public function __construct() {

        $this->modelFactory = new ModelFactory();		
        $this->session      = new Session(Xml::getInstance());

        $this->session->start();
        $model = str_replace('Controller', 'Model', get_class($this)); 

        if(file_exists(BASE_PATH.'/model/'.$model . '.php')) {

            $this->modelFactory->load(str_replace('Controller', '', get_class($this)));

        } else {

            $this->model = NULL;
        }

        $view = str_replace('Controller', 'View', get_class($this));

        $this->view = new View($this->session, $view);
    } 
    public function show_404() {
        require_once BASE_PATH . '/Controller/NotFoundController.php';
        $class = 'NotFoundController';
        $controller = new $class();
        $controller->index();
    }
}
