<?php

class Application_Model_Rate
{
	protected $_currency_id;
	protected $_exchange_id;
	protected $_rate;
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
            throw new Exception('Invalid rate property');
        }
        $this->$method($value);
    }
	
	public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid rate property');
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
	
	public function setCurrencyId($currency_id)
	{
		$this->_currency_id = (int)$currency_id;
		return $this;
	}
	
    public function getCurrencyId()
	{
		return $this->_currency_id;
	}
	
	public function setExchangeId($exchange_id)
	{
		$this->_exchange_id = (int)$exchange_id;
		return $this;
	}
	
    public function getExchangeId()
	{
		return $this->_exchange_id;
	}
 
    public function setRate($rate)
	{
		$this->_rate = (string)$rate;
		return $this;
	}
	
    public function getRate()
	{
		return $this->_rate;
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

