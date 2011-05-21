<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration {
  public function setup() {
    $this->enableAllPluginsExcept('sfPropelPlugin');

    // Check if being executed on UWE servers (cli-compatible for elsewhere).
    if(strpos(__FILE__, '/students/') !== false) {
      self::setUWEGlobals();
    }
  }

  public function getEnvironment() {
    if(strpos($_SERVER['HTTP_HOST'], 'i7.stevelacey.net' ) !== false) {
      return 'dev';
    } else if(strpos(urldecode($_SERVER['REQUEST_URI']), '/~slacey/convert') !== false) {
      return 'demo';
    } else {
      return 'live';
    }
  }
  
  public function configureDoctrine(Doctrine_Manager $manager) {
    $manager->registerConnectionDriver('mysql-alternative-pdo', 'Doctrine_Connection_Mysql_Alternative_PDO');
  }
  
  public function setUWEGlobals() {
    // Standardise UWE variables
    if(php_sapi_name() != 'cli') { 
      $_SERVER['REQUEST_URI'] = str_replace(array('/~slacey', '/convert', '/conv'), '', urldecode($_SERVER['REQUEST_URI']));
      $_SERVER['HTTP_X_FORWARDED_HOST'] = current(explode(', ', $_SERVER['HTTP_X_FORWARDED_HOST']));
      $_SERVER['HTTP_X_FORWARDED_SERVER'] = current(explode(', ', $_SERVER['HTTP_X_FORWARDED_SERVER']));
    }
  }
}
