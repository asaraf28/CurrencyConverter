<?php

class myActions extends sfActions {
  public function execute($request) {
    // Ensure format is set correctly
    $params = $this->getRoute()->getParameters();
    $request->setRequestFormat(isset($params['sf_format']) ? $params['sf_format'] : 'xml');

    // Add layout for xml
    if($request->getRequestFormat() == 'xml') {
      $this->setLayout('layout');
    }
    
    // Add callback for jsonp
    if($request->getRequestFormat() == 'json') {
      $this->callback = $request->getParameter('callback');
    }

    $this->getResponse()->setStatusCode(200);
    return parent::execute($request);
  }
}