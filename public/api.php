<?php

define('ROOT_PATH', __DIR__ . '/../');
define('APPLICATION_START_TIME', microtime(true));

include ROOT_PATH . 'vendor/autoload.php';

$app = new \Microwave\Baltbit\WebScript();
$app->run();
