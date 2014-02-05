<?php

namespace Model;

use \BaseModel;
use \PDOException;

class Home extends BaseModel  
{
    public function getProducts()
    {
        try {
            $this->db->query("SELECT * FROM product");
        } catch(PDOException $e) {
            $this->log->addWarning($e->getMessage());
        }
    }
}
