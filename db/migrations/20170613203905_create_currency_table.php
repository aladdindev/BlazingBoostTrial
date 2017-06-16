<?php

use Phinx\Migration\AbstractMigration;

class CreateCurrencyTable extends AbstractMigration
{
	const CURRENCIES = '{
							"USD": "United States Dollar",
							"AED": "United Arab Emirates Dirham",
							"AUD": "Australian Dollar",
							"CAD": "Canadian Dollar",
							"CHF": "Swiss Franc",
							"CNY": "Chinese Yuan",
							"EUR": "Euro",
							"GBP": "British Pound Sterling",
							"HKD": "Hong Kong Dollar",
							"JPY": "Japanese Yen",
							"KWD": "Kuwaiti Dinar",
							"MXN": "Mexican Peso",
							"NOK": "Norwegian Krone",
							"NZD": "New Zealand Dollar",
							"PAB": "Panamanian Balboa",
							"PLN": "Polish Zloty",
							"RUB": "Russian Ruble",
							"SEK": "Swedish Krona",
							"ZAR": "South African Rand",
							"SAR": "Saudi Riyal"
						}';
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
		$table = $this->table('currencies');
		$table->addColumn('code', 'string', array('limit' => 10))
			->addColumn('description', 'string', array('limit' => 50))
			->create();
			
		$list = json_decode(self::CURRENCIES, true);
		
		$rows = array();
		foreach($list as $key => $value){
			$row = array('code' => $key, 'description' => $value);
			$rows[] = $row;
		}
		
		$this->insert('currencies', $rows);
    }
}
