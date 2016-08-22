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
     * @param string $name How the IRC server identifies itself to clients
     * @param int    $port Port to listen on
     * @param string $bind IP Address to bind to - 0.0.0.0 listens on all IPs
     */
    public function __construct($name, $port = 6667, $bind = '0.0.0.0')
    {
        parent::__construct($bind, $port);
        $this->name = $name;

        $this->addHook(SocketServer::HOOK_DISCONNECT, [$this, 'disconnectHook']);

        PluginRegistrar::attachServer($this);
    }

    /**
     * Return a Connection instance from a Socket
     *
     * @param Socket $client
     *
     * @return Connection
     */
    protected function getConnection(Socket $client)
    {
        $ident = (string)$client;
        if (!isset($this->clientMap[$ident])) {
            $this->clientMap[$ident] = new Connection($this, $client);
        }
        return $this->clientMap[$ident];
    }

    /**
     * Cleanup the Client Map on a client's disconnect
     *
     * @param Server      $server
     * @param Connection  $client
     * @param string|null $message
     */
    protected function disconnectHook(Server $server, Connection $client, string $message = null)
    {
        unset($this->clientMap[$client->getIdentifier()]);
    }

    /**
     * Modify the SocketServer's hook trigger to send along an instance of Connection instead of Socket
     *
     * @param string $command Hook to listen for (e.g. HOOK_CONNECT, HOOK_INPUT, HOOK_DISCONNECT, HOOK_TIMEOUT)
     * @param Socket $client
     * @param string $input   Message Sent along with the Trigger
     *
     * @return bool Whether or not to continue running the server (true: continue, false: shutdown)
     */
    protected function triggerHooks($command, Socket $client, $input = null)
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
