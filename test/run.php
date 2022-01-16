<?php

require_once(__DIR__.'/../vendor/autoload.php');

use ElephpantIRCd\Connection;
use ElephpantIRCd\PluginInterface;
use ElephpantIRCd\PluginRegistrar;
use ElephpantIRCd\Server;

class HelloPlugin implements PluginInterface
{
    public static function register(Server $server)
    {
        $server->addHook(Server::HOOK_CONNECT, function(Server $s, Connection $c, string $message) {
            $c->send('Hello World!');
        });
    }
}

PluginRegistrar::register(HelloPlugin::class);

$server = new Server('irc.localhost');

$server->run();
