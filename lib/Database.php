<?php
/**
 * Copyright 2013 Ryan McCullagh
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
class Database
{
    private static $_objInstance;
    private static $config;
    private function __construct() {} 
    private function __clone() {} 

    public static function getInstance(\Config\Xml $config) { 

        if(!self::$_objInstance) {
            self::$config	= $config;
            $name		= self::$config->database['name'];
            $user		= self::$config->database['user'];
            $password 	        = self::$config->database['password'];

            self::$_objInstance = new PDO('mysql:host=localhost;dbname='.$name, $user, $password); 
            self::$_objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            self::$_objInstance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            self::$_objInstance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,  PDO::FETCH_ASSOC);
        }
        return self::$_objInstance; 
    }
	final public static function __callStatic( $chrMethod, $arrArguments ) { 
  		$objInstance = self::getInstance(); 
    		return call_user_func_array(array($objInstance, $chrMethod), $arrArguments); 
  	}
}
