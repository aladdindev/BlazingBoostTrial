<?php

class Application_Model_OpenExchangeRates
{
	const API_KEY = "287f8b05d8ea4a1db2dc962219899eb9";
	const URL = "https://openexchangerates.org/api/latest.json?app_id=";
	
	/*
	 * Calls a remote currency rates API using HTTP Protocol and saves the 
	 * result to the proper database tables. allow_url_fopen should be true.
	 *
	 * @return Exchange the exchange object
	 */
	public function getRemoteRates(){
		$request_url = self::URL . self::API_KEY;
		
		$content = file_get_contents($request_url);
		$content = json_decode($content, true);
		
		if(isset($content['base']) && isset($content['rates'])){		
			$exchange = new Application_Model_Exchange();
			$exchange->setBase($content['base']);
			
			$exchange_mapper = new Application_Model_ExchangeMapper();
			$exchange_mapper->save($exchange);
			
			$currency_mapper = new Application_Model_CurrencyMapper();
			$available_currencies = $currency_mapper->fetchAll();
			
			$rate_mapper = new Application_Model_RateMapper();
			
			$rates = $content['rates'];
			
			foreach($available_currencies as $currency){
				if(isset($rates[$currency->getCode()])){
					$rate = new Application_Model_Rate();
					$rate->setExchangeId($exchange->getId());
					$rate->setCurrencyId($currency->getId());
					$rate->setRate($rates[$currency->getCode()]);
					
					$rate_mapper->save($rate);
				}
			}
			
			return $exchange;
		}
		
		return false;
	}
}

