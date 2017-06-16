<?php

class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

	public function testCallWithoutActionShouldPullFromIndexAction()
    {
        $this->dispatch('/');
        $this->assertController('index');
        $this->assertAction('index');
    }
	
	public function testIndexShouldContainCurrencyForm()
    {
        $this->dispatch('/');
        $this->assertQueryCount('select', 2);
    }
	
}

