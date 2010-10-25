<?php

class toolkitActions extends sfActions {
  public function executeClearCache(sfWebRequest $request) {
    sfToolkit::clearDirectory(sfConfig::get('sf_cache_dir'));
    $this->redirect('homepage');
  }
}