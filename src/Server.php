<?php

namespace ElephpantIRCd;

use Navarr\Socket\Server as SocketServer;
use Navarr\Socket\Socket;

class Server extends SocketServer
{
    protected $clientMap;
    protected $name;

    /**
     * Server constructor.
     *
     * @param string      $name How the IRC server identifies itself to clients
     * @param int         $port Port to listen on
     * @param string|null $bind IP Address to bind to - 0.0.0.0 listens on all IPs
     */
    public function __construct($name, $port = 6667, $bind = '0.0.0.0')
    {
        parent::__construct($bind, $port);
        $this->name = $name;

        $this->addHook(SocketServer::HOOK_CONNECT, [$this, 'onConnect']);
        $this->addHook(SocketServer::HOOK_INPUT, [$this, 'onInput']);
        $this->addHook(SocketServer::HOOK_DISCONNECT, [$this, 'onDisconnect']);
    }

    public function onConnect(Server $server, Socket $client, $message = null)
    {
        $conn = new Connection($server, $client);
        $this->clientMap[(string)$client] = $conn;
    }

    public function onInput(Server $server, Socket $client, $message)
    {

    }

    public function onDisconnect(Server $server, Socket $client, $message)
    {
        unset($this->clientMap[(string)$client]);
    }
}
