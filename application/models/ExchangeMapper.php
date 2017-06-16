<?php

class Application_Model_ExchangeMapper
{
	protected $_dbTable;
	
	const PRECISION = 6;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable))
            $dbTable = new $dbTable();
		
        if (!$dbTable instanceof Zend_Db_Table_Abstract)
            throw new Exception('Invalid table data gateway provided');
		
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable)
			$this->setDbTable('Application_Model_DbTable_Exchange');
			
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Exchange $exchange)
    {
        $data = array(
            'base'   => $exchange->getBase(),
            'created' => $exchange->getCreated()
        );
 
        if (null === ($id = $exchange->getId())) {
            unset($data['id']);
            $id = $this->getDbTable()->insert($data);
			$exchange->setId($id);
        }
		else
            $this->getDbTable()->update($data, array('id = ?' => $id));
    }
 
    public function find($id, Application_Model_Exchange $exchange)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result))
            return;
		
        $row = $result->current();
        $exchange->setId($row->id)
                  ->setBase($row->base)
                  ->setCreated($row->created);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Exchange();
            $entry->setId($row->id)
                  ->setBase($row->base)
                  ->setCreated($row->created);
            $entries[] = $entry;
        }
        return $entries;
    }
	
	/*
	 * Gets the latest exchange row whose creation time happened
	 * less than 1 hour from the current time
	 *
	 * @return Exchange the exchange row
	 */
	public function fetchLastHour()
	{
		$result = $this->getDbTable()->fetchRow(
			$this->getDbTable()->select()
				->where('created >= DATE_SUB(NOW(),INTERVAL 1 HOUR)')
				->order('created DESC')
		);
		
		if(!$result)
			return false;
		
		$entry = new Application_Model_Exchange();
		$entry->setId($result->id)
			  ->setBase($result->base)
			  ->setCreated($result->created);
			  
		return $entry;
	}
	
	/*
	 * Gets the rates of the exchange from one currency to the base
	 * currency as defined in the exchange table row.
	 * 
	 * @param $exchange the exchange being used
	 * @param $from the code of the currency from which we exchange
	 * @param $to the code of the currency to which we exchange
	 * 
	 * @return array contains the rates of the exchange to the base currency
	 */
	public function getRatesValues($exchange, $from, $to){
		$base = $exchange->getBase();
		
		$currency_mapper = new Application_Model_CurrencyMapper();
		$from_obj = $currency_mapper->fetchByCode($from);
		$to_obj = $currency_mapper->fetchByCode($to);
		
		if($from_obj !== false && $to_obj !== false){
			$rate_mapper = new Application_Model_RateMapper();
			$from_c = -1;
			$to_c = -1;
			if($from_obj->getCode() == $base)
				$from_c = 1;
			else{
				$rate_obj = $rate_mapper->fetchByCurrencyId($from_obj->getId());
				if($rate_obj !== false)
					$from_c = $rate_obj->getRate();
			}
			
			if($to_obj->getCode() == $base)
				$to_c = 1;
			else{
				$rate_obj = $rate_mapper->fetchByCurrencyId($to_obj->getId());
				if($rate_obj !== false)
					$to_c = $rate_obj->getRate();
			}
			
			if($from_c > 0 && $to_c > 0)
				return array('rate_from' => $from_c, 'rate_to' => $to_c);
		}
		
		return false;
	}
	
	/*
	 * Converts one currency to another by looking up the rates of convertion
	 * of both currencies to the base currency and doing the proper
	 * calculation to get the exact value.
	 * 
	 * @param $from the code of the currency from which we exchange
	 * @param $to the code of the currency to which we exchange
	 * @param $value the amount of the currency to convert
	 * 
	 * @return array contains the conversion
	 */
	public function exchange($from, $to, $value)
	{
		if(is_numeric($value) && $value < PHP_INT_MAX){
			$exchange = $this->fetchLastHour();
			if($exchange === false){
				$open_exchange_rates = new Application_Model_OpenExchangeRates();
				$exchange = $open_exchange_rates->getRemoteRates();
			}
			
			if($exchange !== false){
				$rates = $this->getRatesValues($exchange, $from, $to);
				if($rates !== false){
					$to_value = number_format($rates['rate_to'] / $rates['rate_from'] * $value, self::PRECISION);
					
					$response = array(
						'from_currency' => $from,
						'to_currency' => $to,
						'from_value' => $value,
						'to_value' => $to_value
					);
					
					return $response;
				}
				else
					return array('error' => 'Please provide valid currencies');
			}
			else
				return array('error' => 'Error contacting remote server');
		}
		else
			return array('error' => 'Please enter a valid currency value');
	}
}

