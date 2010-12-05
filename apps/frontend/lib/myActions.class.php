<?php

class myActions extends sfActions {
  public function execute($request) {
    if($request->getRequestFormat() == 'xml') {
      $this->setLayout('layout');
    }
    $this->getResponse()->setStatusCode(200);
    return parent::execute($request);
  }
}