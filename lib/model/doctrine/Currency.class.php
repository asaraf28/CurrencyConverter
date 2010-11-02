<?php

class Currency extends BaseCurrency {
  private $country_names = array();

  public function getCountryNames() {
    if(empty($this->country_names)) {
      foreach($this->getCountries() as $country) {
        $this->country_names[] = $country->getName();
      }
    }
    
    return $this->country_names;
  }
}