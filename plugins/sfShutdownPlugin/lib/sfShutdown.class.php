<?php 

if (isset($sf_shutdown_loaded_by_config))
{
  unset($sf_shutdown_loaded_by_config);
}
else
{
  throw new sfException('This class was not loaded properly. It should be loaded by config.php.');
}

class sfShutdown
{
  private static $calls = array();

  /**
   * Calls a function before Symfony shuts down. The parameters behave exactly like call_user_func_array().
   * @param callback $callback
   * @param array $params
   * @see call_user_func_array
   * @author Laurent Bachelier <laurent@bachelier.name>
   */
  public static function add($callback, array $params = array())
  {
    self::$calls[] = array(
      'callback' => $callback,
      'params' => $params,
    );
  }

  /**
   * Calls all the registered shutdown functions.
   * @author Laurent Bachelier <laurent@bachelier.name>
   */
  public static function shutdown()
  {
    foreach (self::$calls as $call)
    {
      call_user_func_array($call['callback'], $call['params']);
    }
  }
}

// Must be called before all of Symfony's register_shutdown_function()
register_shutdown_function(array('sfShutdown', 'shutdown'));
