<?php

namespace greenbot {

    const VERSION = 'alpha v0.1.2';
    const PROJECT = '红 Red';

    date_default_timezone_set('Asia/Shanghai');
    include "ClassLoader.php";
    $loader = new ClassLoader();
    $loader->addpath(dirname(__DIR__));
    $loader->register();

    define('greenbot\\BASE_DIR', str_replace('phar://', '', dirname(dirname(__DIR__))));

    $logger = new console\MainLogger('server.log');
    $logger->start();

    console\MainLogger::info('GreenBot Codename: '.console\TextFormat::RED.'[' . PROJECT . ']'.console\TextFormat::RESET.' Version: ' . VERSION);

    $server = new Server($logger, \greenbot\BASE_DIR);

    

}