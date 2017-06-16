<?php

class ApiController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
		$this->_helper->viewRenderer->setNoRender(true);
    }

    public function exchangeAction()
    {
        // action body
		$from = $this->_request->getParam('from');
		$to = $this->_request->getParam('to');
		$value = $this->_request->getParam('value');
		
		$model = new Application_Model_ExchangeMapper();
		
		$response = $model->exchange($from, $to, $value);
		
		$this->_helper->json($response);
    }
	
	public function listAction()
    {
		$model = new Application_Model_CurrencyMapper();	
		$list = $model->fetchAll();
		
		$response = array();
		foreach($list as $currency){
			$response[] = array('code' => $currency->getCode(), 'description' => $currency->getDescription());
		}
		
		$this->_helper->json($response);
    }


}

