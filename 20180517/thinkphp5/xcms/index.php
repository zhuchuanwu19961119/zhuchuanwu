<?php
namespace think;
define('APP_PATH', __DIR__ . '/application/');
define("RUNTIME_PATH", __DIR__ .'/data/runtime/');
require __DIR__ . '/thinkphp/base.php';
App::run()->send();

