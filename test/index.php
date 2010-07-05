<?php
define('APP', dirname(__FILE__));
define('FRAMEWORK', dirname(APP) . '/framework');
require FRAMEWORK . '/init.php';

Page::dispatch();
?>