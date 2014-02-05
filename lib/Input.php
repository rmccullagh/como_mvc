<?php 
class Input {
 
 public static function ip()
 {
 	return $_SERVER['REMOTE_ADDR'];
 }
 public static function user_agent()
 {
 	return $_SERVER['HTTP_USER_AGENT'];
 }
 public static function validateIntArray(array $stack)
 {
	foreach($stack as $key => $value) {
		if( preg_match('/^[0-9]+$/', $value) === 0)
			return false;
	}
	
	return true;
 }
  
 public static function is_ajax() 
 {
	  //return true;
		return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');	
 }
 public static function postArray(array $keys)
 {	
	$tokens = array();
	$errors = array();
	foreach($keys as $key => $val)
	{	
		$tokens[$key] = (isset($_POST[$key]) AND (filter_var($_POST[$key], $val) != FALSE)) ? self::clean($_POST[$key]) : FALSE;
	}
    foreach($tokens as $key2 => $value2) 
	{
		if($value2 == FALSE)
		{
			$errors[$key2] = $value2;
		}
	}
	return $errors;
  }
	
  public static function post($key, $default = NULL)
	{
		return isset($_POST[$key]) ? self::clean($_POST[$key]) : $default;	
	}
 	public static function get($key, $default = NULL)
	{	
		return isset($_GET[$key]) ? self::clean($_GET[$key]) : $default;
	}
  public static function clean($type)
  {
		if(is_array($type)) {
			foreach($type as $key => $value){	
				$type[$key] = self::clean($value);
			}
			return $type;
		} else {
			$string = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
			return $string;
		}
	}
	public static function request()
	{
		return $_REQUEST;
	}
	
	
	public static function requireToken($session_token)
	{
		// check to see that the token is in the post request
		if(self::post('token') != NULL)
		{
			if(self::post('token') === $session_token)
			{
				return true;
			} 
			else {
				if(!headers_sent()) {
					$location = \Uri\Uri::base_url();
					header("Location: $location");
					exit;
				}
			}
		}
		else
		{	
			// this is not a request on a form. it is the first request
			return true;
		}	
	}
	public static function blockIfLoggedIn(\Session $session, $redirect = NULL)	
	{
		if(isset($session["user"])) {
			if(!headers_sent()) {
				$location = \Uri\Uri::base_url($redirect);
				header("Location: $location");
				exit;
			}
		}
	}
	public static function blockIfNotLoggedIn(\Session $session, $redirect = NULL)
	{
		if(!isset($session['user'])) {
			if(!headers_sent()) {
				$location = \Uri\Uri::base_url($redirect);
				header("Location: $location");
			}
		}
	}
	public static function redirect($location)
	{
		if(!headers_sent()) {
			header("Location: $location");
			exit;
		}
	}
}
