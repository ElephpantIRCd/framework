<?php

require_once(__DIR__.'/../vendor/autoload.php');

$server = new \ElephpantIRCd\Server('irc.localhost');
$server->run();
