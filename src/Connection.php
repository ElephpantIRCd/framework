<?php

namespace ElephpantIRCd;

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

    /**
     * @param string $buffer
     * @return int Bytes sent
     */
    public function send($buffer)
    {
        return $this->socket->send($buffer);
    }
}
