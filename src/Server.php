<?php

declare(strict_types=1);

namespace ElephpantIRCd;

use Navarr\Socket\Exception\SocketException;
use Navarr\Socket\Server as SocketServer;
use Navarr\Socket\Socket;

class Server extends SocketServer
{
    public const DEFAULT_PORT = 6667;
    public const DEFAULT_BIND = '0.0.0.0';

    protected array $clientMap = [];

    /**
     * Server constructor.
     *
     * @param string $name How the IRC server identifies itself to clients
     * @param int $port Port to listen on
     * @param string $bind IP Address to bind to - 0.0.0.0 listens on all IPs
     * @throws SocketException
     */
    public function __construct(protected string $name, int $port = self::DEFAULT_PORT, $bind = self::DEFAULT_BIND)
    {
        parent::__construct($bind, $port);

        $this->addHook(SocketServer::HOOK_DISCONNECT, [$this, 'disconnectHook']);

        PluginRegistrar::attachServer($this);
    }

    /**
     * Return a Connection instance from a Socket
     */
    protected function getConnection(Socket $client): Connection
    {
        $ident = (string)$client;
        if (!isset($this->clientMap[$ident])) {
            $this->clientMap[$ident] = new Connection($this, $client);
        }
        return $this->clientMap[$ident];
    }

    /**
     * Cleanup the Client Map on a client's disconnect
     */
    protected function disconnectHook(Server $server, Connection $client)
    {
        unset($this->clientMap[$client->getIdentifier()]);
    }

    /**
     * Modify the SocketServer's hook trigger to send along an instance of Connection instead of Socket
     *
     * @param string $command Hook to listen for (e.g. HOOK_CONNECT, HOOK_INPUT, HOOK_DISCONNECT, HOOK_TIMEOUT)
     * @param Socket $client
     * @param string|null $input Message Sent along with the Trigger
     *
     * @return bool Whether to continue running the server (true: continue, false: shutdown)
     */
    protected function triggerHooks(string $command, Socket $client, string $input = null): bool
    {
        if (isset($this->hooks[$command])) {
            foreach ($this->hooks[$command] as $callable) {
                $connection = $this->getConnection($client);
                $continue = call_user_func($callable, $this, $connection, $input);

                if ($continue === self::RETURN_HALT_HOOK) {
                    break;
                }
                if ($continue === self::RETURN_HALT_SERVER) {
                    return false;
                }
                unset($continue);
            }
        }

        return true;
    }
}
