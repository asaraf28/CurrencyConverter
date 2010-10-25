<?php

class convertActions extends sfActions {
  private $errors = array (
    '1000' => 'URL not recognized',
    '1100' => 'Required parameter is missing',
    '1200' => 'Parameter not recognized',
    '2000' => 'Currency type not recognized',
    '2100' => 'Currency amount must be a decimal number',
    '3000' => 'Service currently unavailable',
    '3100' => 'Error in service'
  );

  public function executeIndex(sfWebRequest $request) {
    if(!$request->hasParameter('amnt') || !$request->hasParameter('from') || !$request->hasParameter('to')) {
      $this->error = 1100;
    }

    if(isset($this->error)) {
      $this->message = $this->errors[$this->error];
    }

    $this->forward404Unless(false);
  }
}