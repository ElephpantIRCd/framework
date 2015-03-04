<?php

namespace Navarr\IRC;

use Navarr\Socket\Socket;

class Connection
{
    private $server;
    private $socket;

    public function __construct(Server $server, Socket $client)
    {
        $this->server = $server;
        $this->socket = $client;
    }
}
