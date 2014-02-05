<?php
class Request 
{
	protected $uri;

	public function __construct()	
	{
		$this->parse();	
	}
	protected function stripQueryString()
	{
		return preg_replace('#\?.*$#D', '', $_SERVER['REQUEST_URI']);
	}
	protected function parse()
	{
		$uri  = explode('/', $this->stripQueryString());
		$path = explode('/', $_SERVER['SCRIPT_NAME']);

		for($i = 0; $i < count($path); $i++) {
			if($uri[$i] == $path[$i]) {
				unset($uri[$i]);
			}
		}
		$this->uri = implode('/', $uri);
	}
	public function getUri()
	{
		return $this->uri;
	}
}
