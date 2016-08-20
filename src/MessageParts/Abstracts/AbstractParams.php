<?php

namespace ElephpantIRCd\MessageParts\Abstracts;

use \ElephpantIRCd\MessageParts\Interfaces\ParamsInterface;

abstract class AbstractParams implements ParamsInterface
{
    public function __toString()
    {
        return ':' . $this->getMiddle() . $this->getParams();
    }
    abstract public function getMiddle();
    abstract public function getParams();
}
