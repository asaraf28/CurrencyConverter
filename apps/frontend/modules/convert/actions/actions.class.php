<?php

class convertActions extends myActions {
  public function executeIndex(sfWebRequest $request) {
    // Check for additional get parameters
    if(count(array_diff(array_keys($request->getGetParameters()), sfConfig::get('app_convert_params')))) {
      return $this->setError(1000);
    }

    // Check for missing parameters
    if(!$request->hasParameter('amnt') || !$request->hasParameter('from') || !$request->hasParameter('to')) {
      return $this->setError(1100);
    }
    
    // Check for recognised currencies
    $currency = Doctrine::getTable('Currency');
    
    $from = $currency->findOneByCode($request->getParameter('from'));
    $to = $currency->findOneByCode($request->getParameter('to'));
    
    if(!$from instanceOf Currency || !$to instanceOf Currency) {
      return $this->setError(2000);
    }
    
    // Check if amount contains >2 decimal digits.
    $this->amount = $request->getParameter('amnt');
    if(is_numeric($this->amount) && strlen(substr(strrchr($this->amount, '.'), 1)) > 2) {
      return $this->setError(2100);
    }

    // Find cached currency rate
    $transaction = Doctrine::getTable('CurrencyRate')->findOneByFromCodeAndToCode($from, $to);

    // Check if currency rate needs updating
    if(!$transaction instanceOf CurrencyRate || $transaction->getDateTimeObject('updated_at')->format('U') < (time() - (sfConfig::get('app_convert_cache') * 60))) {
      $web = new sfWebBrowser(array(), 'sfCurlAdapter', array('proxy' => sfConfig::get('app_uwe_proxy')));
      $rss = $web->get('http://themoneyconverter.com/'.$from->getCode().'/rss.xml')->getResponseText();
      $xml = new SimpleXMLElement($rss);
      
      $item = $xml->xpath('/rss/channel/item[title="'.$to->getCode().'/'.$from->getCode().'"]');
      $item = is_array($item) ? current($item) : false;
       
      if($item instanceOf SimpleXMLElement) {
        $transaction = $transaction instanceOf CurrencyRate ? $transaction : new CurrencyRate();
        $transaction->setFromCode($from->getCode());
        $transaction->setToCode($to->getCode());
        
        preg_match('/= ([0-9]+\.[0-9]+) /', $item->description, $rate);
        
        $transaction->setRate(trim(current($rate), '= '));
        $transaction->setUpdatedAt(date('Y-m-d H:i:s'));
        $transaction->save();
      } else {
        /*
         * Throws error if themoneyconverter doesn't support 'to' currency
         * TODO: Add fallback scrape, xe.com?
         */
        return $this->setError(2000);
      }
    }

    // Push vars to view
    $this->from = $from;
    $this->to = $to;
    $this->transaction = $transaction;
    $this->result = $this->amount * $transaction->getRate();
  }
  
  public function setError($code) {
    $this->code = $code;
    $this->message = sfConfig::get('app_error_'.$code);
    return sfView::ERROR;
  }
}