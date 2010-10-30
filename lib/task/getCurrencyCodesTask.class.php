<?php

class getCurrencyCodesTask extends sfBaseTask {
  protected function configure() {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'wiki';
    $this->name             = 'get-currency-codes';
    $this->briefDescription = 'Parses Currency Codes from the Wikipedia API';
    $this->detailedDescription = <<<EOF
The [get-currency-codes|INFO] task pulls XML from the Wikipedia API and parses the BBCode.
Call it with:

  [php symfony get-currency-codes|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array()) {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $web = new sfWebBrowser(array(), 'sfCurlAdapter', array('proxy' => sfConfig::get('app_uwe_proxy')));

    $document = $web->get('http://en.wikipedia.org/wiki/Special:Export/ISO_4217', null, array(
      'User-Agent' => 'Steve Lacey <steve@stevelacey.net>',
      'Cache-Control' => 'no-cache'
    ));

    $xml = new SimpleXMLElement($document->getResponseText());
    $article = $xml->page->revision->text;
    $table = substr($article, strpos($article, '{|'), strpos($article, '|}') - strpos($article, '{|'));

    $connection->query('TRUNCATE TABLE '.Doctrine::getTable('Currency')->getTableName());

    foreach(explode('-| ', str_replace(array('[', ']', "\n"), '', $table)) as $row) {
      $row = explode(' || ', trim($row, '| '));

      if(isset($row[2], $row[4]) && is_numeric($row[2])) {
        $currency = new Currency();
        $currency->setCode($row[0]);
        $currency->setNumber($row[1]);
        $currency->setDigits(ceil($row[2]));
        $currency->setName($row[3]);
        $currency->setLocations($row[4]);
        $currency->save();
      }
    }
  }
}