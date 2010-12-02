<?php

class convertActions extends myActions {
  private $cur = 'USD';

  public function executeIndex(sfWebRequest $request) {
    // Check for additional get parameters
    if(count(array_diff(array_keys($request->getGetParameters()), sfConfig::get('app_convert_params')))) {
      return $this->setError(1200);
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
    if(!is_numeric($this->amount) || strlen(substr(strrchr($this->amount, '.'), 1)) > sfConfig::get('app_convert_decimal_result')) {
      return $this->setError(2100);
    }
    
    // Find cached currency rate
    $transaction = Doctrine::getTable('CurrencyRate')->findOneByFromCodeAndToCode($from, $to);

    // Create browser
    $web = new sfWebBrowser(array(), 'sfCurlAdapter', array('proxy' => sfConfig::get('app_web_proxy')));

    // Check if currency rate needs updating
    if(!$transaction instanceOf CurrencyRate || $transaction->getDateTimeObject('updated_at')->format('U') < (time() - (sfConfig::get('app_convert_cache') * 60))) {
      $rss = $web->get('http://themoneyconverter.com/'.$from->getCode().'/rss.xml')->getResponseText();
      $xml = new SimpleXMLElement($rss);
      
      $item = $xml->xpath('/rss/channel/item[title="'.$to->getCode().'/'.$from->getCode().'"]');
      $item = is_array($item) ? current($item) : false;
      
      if(!$transaction instanceOf CurrencyRate) {
        $transaction = new CurrencyRate();
        $transaction->setFromCode($from->getCode());
        $transaction->setToCode($to->getCode());
      }

      if($item instanceOf SimpleXMLElement) {
        preg_match('/= ([0-9]+\.?[0-9]*) /', $item->description, $matches);

        $transaction->setRate($matches[1]);
        $transaction->setUpdatedAt(date('Y-m-d H:i:s'));
        $transaction->save();
      } else {
        // Fallback functionality for rates not surved by themoneyconverter
        $js = $web->get('http://www.bloomberg.com/js/calculators/currdata.js')->getResponseText();
        
        $usd2cur = $this->getRateFromJS($this->cur, $js); // This should always be 1
        $from2cur = $this->getRateFromJS($from->getCode(), $js);
        $to2cur = $this->getRateFromJS($to->getCode(), $js);

        if($usd2cur && $from2cur && $to2cur) {
          $transaction->setRate($usd2cur / $from2cur * $to2cur);
        }
      }

      if($transaction->getRate() > 0) {
        $transaction->save();
      } else {
        return $this->setError(4000);
      }
    }

    // Push vars to view
    $this->from = $from;
    $this->to = $to;
    // We want to be precise for currencies like ZWD where rates are often miniscule, but for other currencies 5 dp is fine
    $this->rate = $transaction->getRate() < 0.00001 ? number_format($transaction->getRate(), sfConfig::get('app_convert_decimal_stored')) : round($transaction->getRate(), sfConfig::get('app_convert_decimal_result'));
    $this->result = sprintf('%0.'.sfConfig::get('app_convert_decimal_result').'f', $this->amount * $this->rate);
    $this->at = $transaction->getDateTimeObject('updated_at');
  }

  public function getRateFromJS($code, $js) {
    preg_match('/price\[\''.$code.':CUR\'\] = ([0-9]+\.?[0-9]*)\;/', $js, $matches);
    return isset($matches[1]) ? $matches[1] : false;
  }
  
  public function setError($code) {
    $this->code = $code;
    $this->message = sfConfig::get('app_error_'.$code);
    return sfView::ERROR;
  }
}