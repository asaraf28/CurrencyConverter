<?php

class generateCurrenciesJSONCacheTask extends sfBaseTask {
  protected function configure() {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'generate';
    $this->name             = 'currencies-json';
    $this->briefDescription = 'Generates the currencies json cache.';
  }

  protected function execute($arguments = array(), $options = array()) {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $cache = sfConfig::get('app_cache_currencies');

    $currencies = array();

    foreach(Doctrine::getTable('Currency')->findAll() as $currency) {
      $currencies[$currency->getCode()] = $currency->getName();
    }

    $json = json_encode($currencies);

    $file = fopen($cache, 'w');
    fwrite($file, $json);
    fclose($file);
    chmod($cache, 0755);
  }
}