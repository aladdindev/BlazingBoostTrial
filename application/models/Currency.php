<?php

class Application_Model_Currency
{
	protected $_code;
	protected $_description;
	protected $_id;
	
	public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
	
	public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid currency property');
        }
        $this->$method($value);
    }
	
	public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid currency property');
        }
        return $this->$method();
    }
	
	public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
	
	public function setCode($code)
	{
		$this->_code = (string)$code;
		return $this;
	}
	
    public function getCode()
	{
		return $this->_code;
	}
 
    public function setDescription($description)
	{
		$this->_description = (string)$description;
		return $this;
	}
	
    public function getDescription()
	{
		return $this->_description;
	}
 
    public function setId($id)
	{
		$this->_id = (int)$id;
		return $this;
	}
	
    public function getId()
	{
		return $this->_id;
	}

}

