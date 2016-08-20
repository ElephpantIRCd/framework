<?php

namespace ElephpantIRCd\MessageParts\Interfaces;

interface ParamsInterface
{
    public function __toString();

    public function getMiddle();
    public function getParams();
}
