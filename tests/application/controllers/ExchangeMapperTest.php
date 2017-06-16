<?php

class ExchangeMapperTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	private $testValid = array("USD", "EUR", 1);
	private $testCurrencyNotValid = array("NOTVALID", "EUR", 100);
	private $testBothNotValid = array("", "NOTVALID", 100);
	private $testSignedValue = array("USD", "EUR", -100);
	private $testZeroValue = array("USD", "EUR", 0);
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }
	
	public function testExchange(){
		
		$model = new Application_Model_ExchangeMapper();
		
		//test valid currency conversion
		$exchange = $model->exchange($this->testValid[0], $this->testValid[1], $this->testValid[2]);
		
		$this->assertTrue($exchange['to_value'] <= 1 && $exchange['to_value'] > 0);
		
		//test invalid currency code
		$exchange = $model->exchange($this->testCurrencyNotValid[0], $this->testCurrencyNotValid[1], $this->testCurrencyNotValid[2]);
		$this->assertArrayHasKey('error', $exchange);
		
		$exchange = $model->exchange($this->testBothNotValid[0], $this->testBothNotValid[1], $this->testBothNotValid[2]);
		$this->assertArrayHasKey('error', $exchange);
		
		//test signed conversion
		$exchange = $model->exchange($this->testSignedValue[0], $this->testSignedValue[1], $this->testSignedValue[2]);
		$this->assertTrue($exchange['to_value'] < 0);
		
		//test signed conversion
		$exchange = $model->exchange($this->testZeroValue[0], $this->testZeroValue[1], $this->testZeroValue[2]);
		$this->assertTrue($exchange['to_value'] == 0);
	}
}