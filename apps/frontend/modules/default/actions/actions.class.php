<?php

class defaultActions extends myActions {
  public function executeError404() {
    $this->code = 1000;
    $this->message = sfConfig::get('app_error_1000');
  }
}