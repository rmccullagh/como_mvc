<?php
use \Input;
ini_set('session.cookie_httponly',  1);
ini_set('session.use_only_cookies',  1);
/**
 * Session class
 *
 * @license MIT
 * @license-year 2012
 * @license-copyright-holder hakre <http://hakre.wordpress.com>
 */
class Session implements ArrayAccess
{
    private $meta = '__meta';
    private static $started;
		private $name;
    public function __construct(\Config\Xml $config)
		{
		$this->config = $config;
		if(isset($this->config->SESSION_NAME)) 
				$this->name = $this->config->SESSION_NAME;

        if (ini_get('session.auto_start')) {
				   	self::$started = true;
            $this->start();
        }
		}
		public function __destruct() {
			session_write_close();
		}
		
		public function regenerate_id()
		{
			session_regenerate_id(true);
		}
		public function start()
    { 
			  if($this->name != NULL)
					session_name($this->name);
				
				session_set_cookie_params(60*60*24*31*2);
				// true or session_start()
				self::$started || session_start();

        (isset($_SESSION[$this->meta]) || $this->init())
            || $_SESSION[$this->meta]['activity'] = $_SERVER['REQUEST_TIME'];
				self::$started = true;
			 	
				if(isset($this['__meta']['user_agent']))
				{
					if($this['__meta']['user_agent'] != md5(Input::user_agent()))
					{
						session_unset();
						$this->destroy();
						session_write_close();
						session_regenerate_id(true);
						$this->generateToken();
					}
				} else {
					session_regenerate_id(true);
				}
		}
    /**
     * write session data to store and close the session.
     */
    public function commit()
    {
        session_commit();
    }

    public function destroy()
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public function get($name, $default = NULL)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return session_name();
    }

		
		public function generateToken()
		{
			$token = rtrim(strtr(base64_encode(mcrypt_create_iv(15,  MCRYPT_DEV_URANDOM)), '+/', '-_'), '=');
			$_SESSION[$this->meta]['token'] = $token;
			//session_write_close();
		}
		

		private function init()
		{
			$token = rtrim(strtr(base64_encode(mcrypt_create_iv(15,  MCRYPT_DEV_URANDOM)), '+/', '-_'), '=');
			$_SESSION[$this->meta] = array(
				'ip'         => $_SERVER['REMOTE_ADDR'],
				'name'       => session_name(),
				'created'    => $_SERVER['REQUEST_TIME'],
				'activity'   => $_SERVER['REQUEST_TIME'],
				'user_agent' => md5($_SERVER['HTTP_USER_AGENT']), 
				'token'      => $token
			);
			$_SESSION['messages'] = array();
			
        return true;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
			  self::$started || $this->start();
        return isset($_SESSION[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
	    self::$started || $this->start();
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
			  self::$started || $this->start();
				$_SESSION[$offset] = $value;
			//	session_write_close();
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
		unset($_SESSION[$offset]);
			//session_write_close();
    }
    public function addMessage(array $messages)
    {
		self::$started || $this->start();
		if(!isset($_SESSION['messages'])) {
			$_SESSION['messages'] = array();	
		}
		$_SESSION['messages'][] = $messages;
	}
	public function getMessage()
	{
		if( !isset($_SESSION['messages']))
			return false;
			
		$message = $_SESSION['messages'];		
		if(count($message) > 0) {
			unset($_SESSION['messages']);
			return $message;
		} else {
			return false;
		}
	}
}
