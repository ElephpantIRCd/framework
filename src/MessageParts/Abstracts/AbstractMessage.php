<?php
namespace ElephpantIRCd\MessageParts\Abstracts;

use ElephpantIRCd\MessageParts\Interfaces\MessageInterface;

abstract class AbstractMessage implements MessageInterface
{
    public function __toString()
    {

    }
}
