<?php

class getCurrenciesTask extends sfBaseTask {
  protected function configure() {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'web';
    $this->name             = 'get-currencies';
    $this->briefDescription = 'Pull Currency data from the Wikipedia API & XE';
    $this->detailedDescription = <<<EOF
The [get-currencies|INFO] task pulls XML from the Wikipedia API & parses HTML from XE.
Call it with:

  [php symfony get-currencies|INFO]
EOF;

    $this->web = null;
  }

  protected function execute($arguments = array(), $options = array()) {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    // Truncate tables to prevent duplicates and relational integrity errors
    $connection->query('TRUNCATE TABLE '.Doctrine::getTable('CurrencyCountry')->getTableName());
    $connection->query('TRUNCATE TABLE '.Doctrine::getTable('Currency')->getTableName());

    $this->web = new sfWebBrowser(array(), 'sfCurlAdapter', array('proxy' => sfConfig::get('app_web_proxy')));

    $this->getCurrencies();
    $this->getCountries();
  }

  protected function getCurrencies() {
    $wikipedia = $this->web->get(sfConfig::get('app_source_currencies'), null, array(
      'User-Agent' => 'Steve Lacey <steve@stevelacey.net>',
      'Cache-Control' => 'no-cache'
    ));

    $xml = new SimpleXMLElement($wikipedia->getResponseText());
    $article = $xml->page->revision->text;
    $table = substr($article, strpos($article, '{|'), strpos($article, '|}') - strpos($article, '{|'));

    foreach(explode('-| ', str_replace(array('[', ']', "\n"), '', $table)) as $row) {
      $row = explode(' || ', trim($row, '| '));

      if(isset($row[2], $row[4]) && is_numeric($row[2])) {
        $currency = new Currency();
        $currency->setCode(sfConfig::get('app_currency_alias_'.$row[0], $row[0]));
        $currency->setNumber($row[1]);
        $currency->setDigits(ceil($row[2]));
        $currency->setName($row[3]);
        $currency->save();
      }
    }
  }

  protected function getCountries() {
    $xe = $this->web->get(sfConfig::get('app_source_countries'), null, array(
      'Cache-Control' => 'no-cache'
    ));

    $doc = new DOMDocument();

    // It's rare you'll have valid XHTML, suppress any errors- it'll do its best.
    @$doc->loadhtml($xe->getResponseText());
    $xpath = new DOMXPath($doc);

    foreach($xpath->query('/html/body//form//select[@name="From"]')->item(0)->getElementsByTagName('option') as $option) {
      $currency = Doctrine::getTable('Currency')->findOneByCode($option->getAttribute('value'));
      $name = substr($option->textContent, 0, strpos($option->textContent, ','));

      if($currency instanceOf Currency && !empty($name)) {
        $country = new CurrencyCountry();
        $country->setCurrency($currency);
        $country->setName($name);
        $country->save();
      }
    }
  }
}