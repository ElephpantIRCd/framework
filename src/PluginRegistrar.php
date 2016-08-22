<?php
namespace ElephpantIRCd;

class PluginRegistrar
{
    const ERRNO_BAD_PLUGIN = 1;
    const ERRNO_ALREADY_ATTACHED = 2;

    /** @var bool Whether or not the plugins have been attached to the server */
    private static $attached = false;

    /** @var string[] Array of plugin classes */
    private static $plugins = [];

    /** @var Server ElephpantIRCd server */
    private static $server;

    /**
     * Register a provided class name as a ElephpantIRCd Plugin.
     *
     * Such a class will be loaded at ElephpantIRCd start
     *
     * @param string $pluginClass
     *
     * @throws \InvalidArgumentException
     * @return bool
     */
    public static function register(string $pluginClass) : bool
    {
        if (!is_subclass_of($pluginClass, PluginInterface::class)) {
            throw new \InvalidArgumentException('Plugin provided must implement \ElephpantIRCd\PluginInterface', static::ERRNO_BAD_PLUGIN);
        }
        if (!in_array($pluginClass, static::$plugins)) {
            static::$plugins[] = $pluginClass;
            if (static::$attached) {
                static::registerPlugin($pluginClass, static::$server);
            }
            return true;
        }
        return false;
    }

    /**
     * Attach a Server to the Registrar.  This will call the
     *
     * @param Server $server
     */
    public static function attachServer(Server $server)
    {
        if (static::$attached) {
            throw new \RuntimeException('Server has already been attached', static::ERRNO_ALREADY_ATTACHED);
        }
        static::$attached = true;
        static::$server = $server;
        foreach(static::$plugins as $class) {
            static::registerPlugin($class, $server);
        }
    }

    private static function registerPlugin(string $class, Server $server)
    {
        return call_user_func([$class, 'register'], $server);
    }
}
