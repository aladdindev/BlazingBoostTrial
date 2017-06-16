<?php

class IndexController extends Zend_Controller_Action
{
	const DEFAULT_CURRENCY_FROM = "USD";
	const DEFAULT_CURRENCY_TO = "EUR";
	const DEFAULT_VALUE = 1;
	
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $currency_model = new Application_Model_CurrencyMapper();	
		$currencies = $currency_model->fetchAll();
		
		$this->view->currencies = $currencies;
		
		$model = new Application_Model_ExchangeMapper();
		$exchange = $model->exchange(self::DEFAULT_CURRENCY_FROM, self::DEFAULT_CURRENCY_TO, self::DEFAULT_VALUE);
		$this->view->exchange = $exchange;
		
		$this->render();
    }


}

