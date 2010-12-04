<?php
// This is the most important step - the point is to include it before the Symfony classes register their shutdown methods
$sf_shutdown_loaded_by_config = 1;
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'sfShutdown.class.php');
