<?php

class generateCurrenciesCSVTask extends sfBaseTask {
  protected function configure() {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'generate';
    $this->name             = 'currencies-csv';
    $this->briefDescription = 'Generates the currencies data table used in the assignment report.';
  }

  protected function execute($arguments = array(), $options = array()) {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    foreach(Doctrine::getTable('Currency')->findAll() as $currency) {
      echo '"',$currency->getCode(),'","',$currency->getName(),'","',implode(', ', $currency->getCountryNames()),'"
';
    }
  }
}