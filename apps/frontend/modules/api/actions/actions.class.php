<?php

class apiActions extends myActions {
  public function executeConvert(sfWebRequest $request) {
    // Check for additional get parameters
    if(count(array_diff(array_keys($request->getGetParameters()), sfConfig::get('app_convert_params')))) {
      return $this->setError(1200);
    }

    // Check for missing parameters
    if(!$request->hasParameter('amnt') || !$request->hasParameter('from') || !$request->hasParameter('to')) {
      return $this->setError(1100);
    }
    
    // Check for recognised currencies
    $currency = Doctrine::getTable('Currency'); /* @var $currency Doctrine_Table */
    
    $this->from = $currency->findOneByCode($request->getParameter('from'));
    $this->to = $currency->findOneByCode($request->getParameter('to'));
    $this->amount = $request->getParameter('amnt');
    
    if(!$this->from instanceOf Currency || !$this->to instanceOf Currency) {
      return $this->setError(2000);
    }
    
    // Check if amount contains >2 decimal digits.
    if(!is_numeric($this->amount) || strlen(substr(strrchr($this->amount, '.'), 1)) > sfConfig::get('app_convert_decimal_result')) {
      return $this->setError(2100);
    }
    
    // Find cached currency rate
    $transaction = Doctrine::getTable('CurrencyRate')->getCurrencyRate($this->from, $this->to); /* @var $transaction CurrencyRate */

    // Check if currency rate needs updating
    if($transaction->isNew() || $transaction->isOutdated()) {
      $transaction->setRate($this->getMoneyConverterRate());

      if(!$transaction->getRate()) {
        // Fallback functionality for rates not surved by themoneyconverter
        $transaction->setRate($this->getBloombergRate());
      }

      if($transaction->getRate() > 0) {
        $transaction->setUpdatedAt(date('Y-m-d H:i:s'));
        $transaction->save();
      } else {
        return $this->setError(4000);
      }
    }

    // We want to be precise for currencies like ZWD where rates are often miniscule, but for other currencies 5 dp is fine
    $this->rate = $transaction->getRate() < 0.00001 ? number_format($transaction->getRate(), sfConfig::get('app_convert_decimal_stored')) : round($transaction->getRate(), sfConfig::get('app_convert_decimal_result'));
    $this->result = sprintf('%0.'.sfConfig::get('app_convert_decimal_result').'f', $this->amount * $this->rate);
    $this->at = $transaction->getDateTimeObject('updated_at');
  }

  public function getMoneyConverterRate() {
    $rss = $this->getData(sfConfig::get('app_cache_moneyconverter'), sfConfig::get('app_source_rates_rss').'/'.$this->from->getCode().'/rss.xml');
    $xml = new SimpleXMLElement($rss);

    if($xml instanceOf SimpleXMLElement) {
      return $this->getRateFromXML($this->from, $this->to, $xml);
    } else {
      return false;
    }
  }

  public function getBloombergRate() {
    $js = $this->getData(sfConfig::get('app_cache_bloomberg'), sfConfig::get('app_source_rates_js'));

    $usd2cur = $this->getRateFromJS(sfConfig::get('app_currency_base'), $js); // This should always be 1
    $from2cur = $this->getRateFromJS($this->from->getCode(), $js);
    $to2cur = $this->getRateFromJS($this->to->getCode(), $js);

    if($usd2cur && $from2cur && $to2cur) {
      return $usd2cur / $from2cur * $to2cur;
    } else {
      return false;
    }
  }

  public function getData($cache, $url) {
    if(!file_exists($cache) || filemtime($cache) < time() - sfConfig::get('app_cache_file')) {
      if(is_null($this->web)) {
        $this->web = new sfWebBrowser(array(), 'sfCurlAdapter', array('proxy' => sfConfig::get('app_web_proxy')));
      }

      try {
        if (!$this->web->get($url)->responseIsError()) {
          // Successful response (eg. 200, 201, etc)
          $content = $this->web->getResponseText();

          $file = fopen($cache, 'w');
          fwrite($file, $content);
          fclose($file);
          return $content;
        } else {
          // Error response (eg. 404, 500, etc)
        }
      } catch (Exception $e) {
        // Adapter error (eg. Host not found)
      }
    }

    // Gracefully ignore any errors and return cache
    $file = fopen($cache, 'r');
    return fread($file, filesize($cache));
  }

  public function getRateFromJS($code, $js) {
    preg_match('/price\[\''.$code.':CUR\'\] = ([0-9]+\.?[0-9]*)\;/', $js, $matches);
    return isset($matches[1]) ? $matches[1] : false;
  }

  public function getRateFromXML($from, $to, $xml) {
    $item = $xml->xpath('/rss/channel/item[title="'.$to->getCode().'/'.$from->getCode().'"]');
    $item = is_array($item) ? current($item) : false;

    if($item instanceOf SimpleXMLElement) {
      preg_match('/= ([0-9]+\.?[0-9]*) /', $item->description, $matches);
    }

    return isset($matches[1]) ? $matches[1] : false;
  }
  
  public function setError($code) {
    $this->code = $code;
    $this->message = sfConfig::get('app_error_'.$code);
    return sfView::ERROR;
  }
}