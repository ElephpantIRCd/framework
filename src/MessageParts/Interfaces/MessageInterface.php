<?php
namespace ElephpantIRCd\MessageParts\Interfaces;

interface MessageInterface
{
    public function __toString();
    public function getPrefix();
    public function getCommand();
    public function getParams();
    public function getEol();
}
