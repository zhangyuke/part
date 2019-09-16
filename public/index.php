<?php



namespace think;

define('WEB_URL','https://jz.hqsqxx.com');

require __DIR__ . '/../thinkphp/base.php';

Container::get('app')->run()->send();
