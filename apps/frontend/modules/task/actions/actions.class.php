<?php

class taskActions extends myActions {
  public function executeCc(sfWebRequest $request) {
    sfToolkit::clearDirectory(sfConfig::get('sf_cache_dir'));
    $this->redirect('homepage');
  }

  public function executeGetCurrencies(sfWebRequest $request) {
    chdir(sfConfig::get('sf_root_dir')); // Trick plugin into thinking you are in a project directory
    $task = new getCurrenciesTask(sfContext::getInstance()->getEventDispatcher(), new sfFormatter());
    $task->run(array(), array());
  }
}