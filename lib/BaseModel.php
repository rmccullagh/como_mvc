<?php
use \Database;
use \Config\Xml;
abstract class BaseModel {
        protected $db;
        protected $config;
        public function __construct() {
            try {
                $this->db = Database::getInstance(Xml::getInstance()); 
            } catch(PDOException $e) {
                $this->db = NULL;
            }
            $this->config = Xml::getInstance();
        }
}
