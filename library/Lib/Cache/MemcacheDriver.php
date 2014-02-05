<?php

namespace Lib\Cache;

use Lib\Cache\CacheInterface;
use Lib\Cache\MemcacheDriverException;
use \Memcache;

class MemcacheDriver extends Memcache implements CacheInterface 
{
    protected $memcache;
    protected $required_config = array(
        "host" => "host",  
        "port" => "port"
    );
    protected $host;
    protected $port;

    public function __construct(array $config) {        
        if(!is_array($config)) {
            throw new MemcacheDriverException("configuration arguments must be an array");
        } 
        foreach($config as $key => $value) {
            if(!isset($this->required_config[$key])) {
                throw new MemcacheDriverException("Invalid configration parameters passed to constructor" );
            } else {
                $this->{$key} = $value; 
            }
        }
        $this->addServer($this->host, $this->port);
    }
    public function get($key) {
        return parent::get($key);
    }
    public function set($key, $data, $expire) {
        return parent::set($key, $data, $expire);
    }
    public function flush() {
        return parent::flush();
    }
}





