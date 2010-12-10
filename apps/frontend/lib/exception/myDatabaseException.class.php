<?php

class myDatabaseException {
  public function printStackTrace() {
    $this->code = 3000;
    $this->message = sfConfig::get('app_error_3000');
  }
}