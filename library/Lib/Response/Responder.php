<?php
/**
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * @author      Ryan McCullagh <ryan@ryanmccullagh.com>
 * @copyright   2014 Ryan McCullagh
 * @link        http://github.com/rmccullagh/como_mvc
 * @license     http://www.apache.org/licenses/LICENSE-2.0 
 */

namespace Lib\Response;

use Lib\Response\ResponderException;

class Responder
{
    protected $vars         = array(); 
    protected $content_type = 'text/html';
    protected $charset      = 'UTF-8';
    protected $paglet_map   = array();
    protected $path;

    public function __construct(array $vars)
    {
        foreach($vars as $key => $value) {
            $this->vars[$key] = $value;
        }
    }
    public function setContentType($type) 
    {
        $this->content_type = $type;
        return $this; 
    }
    public function getContentType()
    {
        return $this->content_type;
    }
    public function setCharset($charset)
    {
        $this->charset = $charset; 
        return $this;
    }
    public function setPaglet($paglet)
    {
        $this->paglet_map[] = $this->createPath($paglet);
        return $this;
    }
    public function setPagletArray(array $paglets)
    {
        foreach($paglets as $key => $value) {
            $this->paglet_map[] = $this->createPath($value);
        }
        return $this;
    }
    public function getPaglets()
    {
        return $this->paglet_map;
    }
    public function setPath($path)
    {
        $path = preg_replace('/[^a-zA-Z0-9]/', '', $path);
        $dir  = VIEW_PATH . DIRECTORY_SEPARATOR . $path;
        if(! is_dir($dir)) {
            throw new ResponderException($dir . ' is not a directory');
        } else {
           $this->path = $dir;
        }
        return $this;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function renderOutput()
    {
        $final_output = '';

        extract($this->vars,  EXTR_PREFIX_SAME,   "wddx");

        if(! headers_sent()) {
            header("Content-Type: ". $this->getContentType());
        }

        ob_start();
        
        while($paglet = array_shift($this->paglet_map)) {
            if(! file_exists($paglet)) {
                throw new ResponderException("Failed to find " . $paglet);
            } else {
                $final_output .= include($paglet);
            }
        }

        echo ob_get_clean();
    }
    protected function createPath($name)
    {
        $path = $this->getPath() . DIRECTORY_SEPARATOR . $name . EXT;
        if(!file_exists($path)) {
            throw new ResponderException("failed to find $path");
        }
        return $path;
    }
}
