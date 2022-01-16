<?php

namespace ElephpantIRCd;

use Navarr\Socket\Socket;

class Connection
{
    private array $data = [];

    public function __construct(private Server $server, private Socket $client)
    {
    }

    public function getIdentifier()
    {
        return (string)$this->client;
    }

    /**
     * @param string $buffer
     * @return int Bytes sent
     */
    public function send($buffer)
    {
        return $this->client->send($buffer);
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
