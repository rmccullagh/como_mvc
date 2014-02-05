<?php
class ModelFactory extends BaseModel implements ArrayAccess {
	public function __construct()
	{
		parent::__construct();
	}
	public function load($file)
	{
		//$file = str_replace(' ', '', ucwords(str_replace('_', ' ', $file)).'Model.php');

		// convert arguments matching this pattern: string1_string_2
		// to a path name
		$classPath = explode('_', $file);
		$classPath = array_map("ucfirst", $classPath);
		$classPath = implode('/', $classPath).'Model';

		//$class = str_replace('.php', '', $file);
		$className = explode('/', $classPath);
		//array_shift($className);
		if(count($className) > 1)
			$class = $className[1];
	  else
			$class = $className[0];
   
		if(!isset($this->{$class})) 
		{
			
			if(file_exists(BASE_PATH . '/Model/' . $classPath . '.php'))
			{
				include BASE_PATH . '/Model/' . $classPath . '.php';
				
				//$class = str_replace('.php', '', $file);

				$this->{$class} = new $class();

				return $this->{$class};
			} 
			else
			{
	      echo "Fuck there was no $classPath  found";
			}
		} 
		else 
		{
			return $this->{$class};
		}
	}

	public function get($_model)
	{ 
		$model = ucfirst($_model).'Model';
		if(isset($this->{$model})) {
			return $this->{$model};
		} else {
			return $this->load($_model);
		}
	}
	public function offsetExists($offset)
	{
		return isset($this->{$offset});
	}
	public function offsetGet($offset)
    {
		return $this->get($offset);
	}
	
	public function offsetSet($offset,  $value) 
	{
		return true;
	}
	public function offsetUnset($offset)
	{
		return true;
	}
    public function getDb() {
        return $this->db;
    }
}
