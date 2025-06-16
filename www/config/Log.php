<?php

namespace Config;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class Log
{
    public static function getLogger(): Logger
    {
        $logger = new Logger('app');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Level::Debug));
        return $logger;
    }
}