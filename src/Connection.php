<?php

namespace ElephpantIRCd;

use Navarr\Socket\Socket;

class Connection
{
    private $server;
    private $socket;

    private $data;

    public function __construct(Server $server, Socket $client)
    {
        $this->server = $server;
        $this->socket = $client;
    }

    public function getIdentifier()
    {
        return (string)$this->socket;
    }

    /**
     * @param string $buffer
     * @return int Bytes sent
     */
    public function send($buffer)
    {
        return $this->socket->send($buffer);
    }

    public function getData($key)
    {
        return $this->data[$key];
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function hasData($key)
    {
        return isset($this->data[$key]);
    }
}
