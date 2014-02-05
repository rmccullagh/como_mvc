<?php

class Router
{
	protected $routes = array();

	public function __construct() 
	{
	}
	public function bindTo($requestUri, $objectName)
	{
		$this->routes[$requestUri] = $objectName;
	}
	public function getRoutingTable()
	{
		return $this->routes;
	}
}
