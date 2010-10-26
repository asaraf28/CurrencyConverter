<?php

class defaultActions extends sfActions {
  private $errors = array (
    1000 => 'URL not recognized',
    1100 => 'Required parameter is missing',
    1200 => 'Parameter not recognized',
    2000 => 'Currency type not recognized',
    2100 => 'Currency amount must be a decimal number',
    3000 => 'Service currently unavailable',
    3100 => 'Error in service'
  );

  public function executeError404() {
    $this->setError(1000);
    $this->getResponse()->setStatusCode(200);
  }

  public function setError($code) {
    $this->code = $code;
    $this->message = $this->errors[$code];
  }
}