<?php

declare(strict_types=1);

namespace ElephpantIRCd;

trait Hookable
{
    private iterable $hooks = [];

    /**
     * Triggers the hooks for the supplied command.
     *
     * @param string $command Hook to listen for (e.g. HOOK_CONNECT, HOOK_INPUT, HOOK_DISCONNECT, HOOK_TIMEOUT)
     * @param iterable $data
     * @return bool Whether to continue running the server (true: continue, false: shutdown)
     */
    protected function triggerHooks(string $command, iterable $data): bool
    {
        if (isset($this->hooks[$command])) {
            foreach ($this->hooks[$command] as $callable) {
                $continue = call_user_func($callable, $this, $data);

                if ($continue === false) {
                    break;
                }
                unset($continue);
            }
        }

        return true;
    }

    /**
     * Attach a Listener to a Hook.
     *
     * @param string $command Hook to listen for
     * @param callable(self,iterable):bool $callable A callable with the signature (Server, Socket, string). Callable
     * should return false if it wishes to stop the server, and true if it wishes to continue.
     * @return void
     */
    public function addHook(string $command, callable $callable): void
    {
        if (!isset($this->hooks[$command])) {
            $this->hooks[$command] = [];
        } else {
            $k = in_array($callable, $this->hooks[$command]);
            if ($k !== false) {
                return;
            }
            unset($k);
        }

        $this->hooks[$command][] = $callable;
    }

    /**
     * Remove the provided Callable from the provided Hook.
     *
     * @param string $command Hook to remove callable from
     * @param callable $callable The callable to be removed
     * @return void
     */
    public function removeHook(string $command, callable $callable): void
    {
        if (isset($this->hooks[$command])
            && in_array($callable, $this->hooks[$command])
        ) {
            $hook = array_search($callable, $this->hooks[$command]);
            unset($this->hooks[$command][$hook]);
            unset($hook);
        }
    }
}
