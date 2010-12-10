<?php

class defaultActions extends myActions {
  public function executeError404() {
    $this->code = 1000;
    $this->message = sfConfig::get('app_error_1000');
  }

  public function executeError500() {
    $this->code = 3100;
    $this->message = sfConfig::get('app_error_3100');
  }
}