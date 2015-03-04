<?php

namespace Navarr\IRC;
use \Navarr\Socket\Server as SocketServer;
use Navarr\Socket\Socket;

class Server extends SocketServer
{
    protected $clientMap;
    protected $name;

    public function __construct($name, $port = 6667, $bind = null)
    {
        parent::__construct($bind, $port);
        $this->name = $name;

        $this->addHook(SocketServer::HOOK_CONNECT, array($this, 'onConnect'));
        $this->addHook(SocketServer::HOOK_INPUT, array($this, 'onInput'));
        $this->addHook(SocketServer::HOOK_DISCONNECT, array($this, 'onDisconnect'));
    }

    public function onConnect(Server $server, Socket $client, $message)
    {
        $this->clientMap[(string)$client] = new Connection($server, $client);
    }

    public function onInput(Server $server, Socket $client, $message)
    {
        // TODO
    }

    public function onDisconnect(Server $server, Socket $client, $message)
    {
        unset($this->clientMap[(string) $client]);
    }
}
