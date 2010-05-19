<?php
if (!defined('FRAMEWORK_PATH')){define('FRAMEWORK_PATH', dirname(__FILE__));}
if (!defined('DEBUG')){define('DEBUG', false);}

require(FRAMEWORK_PATH . '/common.php');
require(FRAMEWORK_PATH . '/class/core/autoload.php');
autoload::register();

uri::parse();

?>