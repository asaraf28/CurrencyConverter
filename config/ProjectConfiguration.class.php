<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration {
  public function setup() {
    $this->enableAllPluginsExcept('sfPropelPlugin');

    if(strpos($_SERVER['HTTP_HOST'], 'uwe.ac.uk') !== false) {
      ProjectConfiguration::setUWEGlobals();
    }
  }

  public function getEnvironment() {
    if(strpos($_SERVER['HTTP_HOST'], '.stevelacey.net' ) !== false) {
      return 'dev';
    } else if(strpos(urldecode($_SERVER['REQUEST_URI']), '/~slacey/convert') !== false) {
      return 'demo';
    } else {
      return 'live';
    }
  }

  public function setUWEGlobals() {
    // Standardise UWE variables
    $_SERVER['REQUEST_URI'] = str_replace(array('/~slacey', '/%7Eslacey', '/convert', '/conv'), '', $_SERVER['REQUEST_URI']);
    $_SERVER['HTTP_X_FORWARDED_HOST'] = current(explode(', ', $_SERVER['HTTP_X_FORWARDED_HOST']));
    $_SERVER['HTTP_X_FORWARDED_SERVER'] = current(explode(', ', $_SERVER['HTTP_X_FORWARDED_SERVER']));
  }
}