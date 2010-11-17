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

    $this->web = new sfWebBrowser(array(), 'sfCurlAdapter', array('proxy' => sfConfig::get('app_uwe_proxy')));
  }

  protected function execute($arguments = array(), $options = array()) {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    // Truncate tables to prevent duplicates and relational integrity errors
    $connection->query('TRUNCATE TABLE '.Doctrine::getTable('CurrencyCountry')->getTableName());
    $connection->query('TRUNCATE TABLE '.Doctrine::getTable('Currency')->getTableName());

    $this->getCurrencies();
    $this->getCountries();
  }

  protected function getCurrencies() {
    $wikipedia = $this->web->get('http://en.wikipedia.org/wiki/Special:Export/ISO_4217', null, array(
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
        $currency->setCode(str_replace('ZWL', 'ZWD', $row[0])); // The ISO 4217 spec lists the Zimbabwean Dollar incorrectly, everywhere else accepts it as ZWD.
        $currency->setNumber($row[1]);
        $currency->setDigits(ceil($row[2]));
        $currency->setName($row[3]);
        $currency->save();
      }
    }
  }

  protected function getCountries() {
    $xe = $this->web->get('http://www.xe.com/ucc/full', null, array(
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