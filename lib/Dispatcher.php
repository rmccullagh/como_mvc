<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Dispatcher 
{	
	protected $request;
	protected $router;
	protected $class;
	protected $method = 'index';
	protected $args;
        protected $controller_path;
        protected $log;
	protected $messages = array();

	public function __construct(\Request $request, \Router $router)
	{
		$this->request = $request;
		$this->router  = $router;
                $this->controller_path = BASE_PATH . '/controller/';
                $log = new Logger('dispatcher');           
                $log->pushHandler(new StreamHandler(BASE_PATH.'/logs/app.log',   Logger::DEBUG));
                $this->log = $log;
	}

	public function prepare()
	{	
		$mapType = explode('/', $this->request->getUri());

		if(self::isRoot($mapType)) 
                {
                    $this->log->addInfo("Found Empty route, executing default controller");
		    $this->invokeRoot();
		    return;
		} 
		else 
		{
			$routes = $this->router->getRoutingTable();
			$uri = $this->request->getUri();
			foreach($routes  as $key => $value) 
			{	
				if(preg_match($key, $uri)) 
				{
					$parts = explode('/', $value);
					$this->class  = str_replace(' ', '', ucfirst(str_replace('_', ' ', $parts[0]))).'Controller';
					$this->method = $parts[1];
					$this->args   = $mapType;			
					return;
				}	
			}
		}

		for($i = 0; $i < count($mapType); $i++) {
			if($mapType[$i] == '') {
				unset($mapType[$i]);
				$mapType = array_values($mapType);
			}
		}
		if(count($mapType) > 0) {
			$parts = preg_split('/-/', $mapType[0]);
			$name = '';
			foreach($parts as $key => $value)
				$name .= ucfirst($value);
			$this->class = $name.'Controller';
			if(isset($mapType[1])) {
				$this->method = $mapType[1];				
			} else {
				$this->method = 'index';
			}
			
			$args = array_slice($mapType, 2);
			
			if(is_array($args)) {
					foreach($args as $key => $arg)
						$this->args[] = $arg;
			}
			/*
			if(isset($mapType[2])) {
				$this->args[] = $mapType[2];
			}
			*/
		} 
	}

	private function invokeRoot()
	{
		$this->class  = 'HomeController';
		$this->method = 'index';
	}

	public static function isRoot(array $node)
	{	
		return count($node) == 1 && $node[0] == '';
	}

        public function execute()
        {
            $class  = $this->class;
            $method = $this->method;
            if(method_exists('BaseController', $method) OR ( isset($method) AND strlen($method) > 0 AND $method[0] === "_")) {
                $this->log->addWarning("Attempt to request method that exists in BaseController, aborting");
                $this->show_404();
            }

            if(file_exists(BASE_PATH . '/controller/'.$class.'.php')) {
                require_once BASE_PATH . '/controller/'.$class.'.php';
                try {
                    $reflectionClass = new ReflectionClass($class); 
                    if(! $reflectionClass->IsInstantiable()) {
                        $this->log->addWarning($class . " is not instantiable, aborting");
                        $this->show_404();
                    }
                    $classInstance = $reflectionClass->newInstance();
                    $method = str_replace('-','_', $method);
                    if($reflectionClass->hasMethod($method)) {
                        $reflectionMethod = new ReflectionMethod($classInstance, $method);
                        if(! $reflectionMethod->isPublic()) {
                            $this->log->addWarning($reflectionMethod->class . '::'.$reflectionMethod->name . ' is not public');
                            $this->show_404();
                        }
                        $realArgCount    = $reflectionMethod->getNumberOfParameters();
                        $requestArgCount = count($this->args);
                        if($this->args) {
                            if($realArgCount === $requestArgCount) {

                                $reflectionMethod->invokeArgs($classInstance, $this->args); 
                            } else {
                                $object = $reflectionMethod->class.'::'.$reflectionMethod->name;

                                $this->log->addWarning("Invalid number of arguments provided, ". $object . " expects ". $realArgCount);
                                $this->show_404();
                            }
                        } else {
                            if($realArgCount === $requestArgCount) {
                                $reflectionMethod->invoke($classInstance); 
                            } else {
                                $this->show_404();
                            }
                        }                        
                    } else {
                        $this->show_404();
                    }
                } catch(ReflectionException $e) {
                    var_dump($e);
                    $this->show_404();
                }
            } else {
                $this->show_404();
            }
        }

	private function show_404()
	{
		require_once BASE_PATH . '/controller/NotFoundController.php';
		$controller = new NotFoundController();
		$controller->index();
		exit;
	}
}
