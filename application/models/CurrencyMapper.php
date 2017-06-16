<?php

class Application_Model_CurrencyMapper
{
	protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Currency');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Currency $currency)
    {
        $data = array(
            'code'   => $currency->getCode(),
            'description' => $currency->getDescription()
        );
 
        if (null === ($id = $currency->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Currency $currency)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $currency->setId($row->id)
                  ->setCode($row->code)
                  ->setDescription($row->description);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Currency();
            $entry->setId($row->id)
                  ->setCode($row->code)
                  ->setDescription($row->description);
            $entries[] = $entry;
        }
        return $entries;
    }
	
	public function fetchByCode($code){
		$result = $this->getDbTable()->fetchRow(
			$this->getDbTable()->select()
				->where('code = ?', $code)
		);
		
		if(!$result)
			return false;
		
		$entry = new Application_Model_Currency();
		$entry->setId($result->id)
			  ->setCode($result->code)
			  ->setDescription($result->description);
			  
		return $entry;
	}
}

