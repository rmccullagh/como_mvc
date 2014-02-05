<?php
use \Database;
use \Config\Xml;
use \PDOException;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

abstract class BaseModel {
    protected $db;
    protected $config;
    protected $log;

    public function __construct() {
        
        $log = new Logger('database');
        $log->pushHandler(new StreamHandler(BASE_PATH.'/logs/db.log', Logger::DEBUG));
        $log->addInfo("Initializing BaseModel @ " . microtime(true));
        $this->log = $log;
        
        try {
            $this->db  = Database::getInstance(Xml::getInstance());
        } catch(PDOException $e) {
            echo $e->getMessage();
            $this->db = NULL;
        }
        $this->config = Xml::getInstance();
    }
}
