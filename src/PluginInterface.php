<?php

namespace ElephpantIRCd;

interface PluginInterface
{
    /**
     * When implementing this interface, it is suggested that
     *
     * @param Server $server
     * @return mixed
     */
    public static function register(Server $server);
}
