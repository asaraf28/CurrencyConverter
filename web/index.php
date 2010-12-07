<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$env = ProjectConfiguration::getEnvironment();

if(in_array($env, array('dev', 'demo'))) {
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
}

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $env, true);
sfContext::createInstance($configuration)->dispatch();
