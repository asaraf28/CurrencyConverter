<?php

class myException extends sfException {
  public function printStackTrace() { //called by sfFrontWebController when an sfException is thrown
    $response = sfContext::getInstance()->getResponse();
    if (null === $response) {
      $response = new sfWebResponse(sfContext::getInstance()->getEventDispatcher());
      sfContext::getInstance()->setResponse($response);
    }

    $response->setStatusCode(200);
  }
}