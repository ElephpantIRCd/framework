<?php
namespace ElephpantIRCd\MessageParts;

use ElephpantIRCd\MessageParts\Interfaces\EOLInterface;

class CRLF implements EOLInterface
{
    const CR = "\r";
    const LF = "\n";

    public function __toString()
    {
        return static::CR.static::LF;
    }
}
