<?php
use \Input;
use \Uri;
class View extends BaseView {
	protected $file;
	protected $values = array();
        protected $sideBar;
        protected $header;
        protected $footer;
        protected $_file;
        protected $output_string;


	public function __construct(\Session $session, $file)
	{
		parent::__construct();
                $this->_file = BASE_PATH . '/view/' . $file . '.php';
                if(!file_exists($this->_file)) {
                    $this->_file = NULL;
                }
		$this->session = $session;
		$this->values['user'] = array(
		  "id" => $this->session['user'], 
		  "email" => $this->session['email'],
	 	  "token" => $this->session['__meta']['token'],
	 	  "last_login" => $this->session['last_login']
		);
        }
        public function renderAsString($string) {
            
            $this->output_string = $string;

            extract($this->values, EXTR_PREFIX_SAME,  "wddx");
            
            ob_start();
            
            eval(' ?>'.$this->output_string.'<?php ');

            
            echo ob_get_clean();


        }
	public function file($file)
	{
		$file = str_replace(' ', '', ucwords(str_replace('_', ' ', $file)).'View');
		$this->_file = BASE_PATH . '/view/' . $file . '.php';
		return $this;
	}
  public function set($value)
	{	
		foreach($value as $key => $item)
		{
			$this->values[$key] = $item;
		}
		return $this;
	}
	public function render()
	{
		if(Input::is_ajax()) {
		echo json_encode(array(
				"__arr"    => 1, 
				"payload" => $this->values, 
				"markup"  => $this->_render()
			));
		} else {
                    //$this->getGlobalUserMenu();
                    if($this->_file != NULL)
			echo $this->_render();
		}
	}
	private function getGlobalUserMenu()
	{
		$this->values['user'] = array();
		
		// have a 2d array with all user data in order to grab
		// it all at once
		$user = new User($this->session);
		if($user->isLoggedIn()) {
			$this->values['user']['token'] = $this->session['__meta']['token'];
			$this->values['user']['email'] = "None";
			extract($this->values['user']);
			ob_start();
			$html = include BASE_PATH . '/view/' . 'GlobalAuthMenuLoggedIn.php';
			$this->values['menu'] = ob_get_clean();
			unset($this->values['user']);
			return;
		} else {
			ob_start();
			$html = include BASE_PATH . '/view/' . 'GlobalAuthMenuLoggedOut.php';
			$this->values['menu'] = ob_get_clean();
			return;
		}
	}
	private function _render()
	{
		//ob_start();
            extract($this->values, EXTR_PREFIX_SAME,  "wddx");
			if($this->sideBar) {
				$this->returned .= include($this->sideBar);
			}
		if(Input::is_ajax()) {
			ob_start();
			if($this->_file)
				$markup = include($this->_file);
		  return ob_get_clean();		
		}	else {
			ob_start();
			$this->returned = '';
			if($this->header) {
				$this->returned  = include($this->header);
			}
			if($this->_file) {
				$this->returned .= include($this->_file);
			}

			if($this->footer) {
				$this->returned .= include($this->footer);
			}
			return ob_get_clean();
		}
	}
	public function setContainer($file)
	{
		if($file)
		{
		//var_dump(preg_match('/^[a-z_]+$/', $file));
		$matches = explode('/',$file);
		foreach($matches as $key => $value)
		{
			if($value == NULL)
			{
				unset($matches[$key]);
			}
		}
		$matches = array_map(function($a) {
			return str_replace(' ', '', ucwords(str_replace('_', ' ', $a)));
		}, $matches);
		
		$this->_file = VIEW_PATH . implode('/', $matches) . EXT;
	    } else {
			$this->_file = FALSE;
		}
		return $this;
	}
	public function setHeader($header)
	{
		if($header) {
			$this->header = BASE_PATH . '/View/' . ucfirst($header).'View.php';
		} else {
			$this->header = FALSE;
		}
		return $this;
	}
	public function setFooter($file)
	{	
		if($file) {
			$this->footer = BASE_PATH . '/View/' . ucfirst($file).'View.php';
		} else {
			$this->footer = FALSE;
		}
		return $this;
	}
	public function setSideBar($file) {
		if($file) {
			$this->sideBar = BASE_PATH . '/View/' . $file.'View.php';
		} else {
			$this->sideBar = FALSE;
		}
		return $this;			
	}
}
