<?php

namespace Dyln\Slim\Http;

class Stream extends \Slim\Http\Stream
{
    protected function attach($newStream)
    {
        if (is_string($newStream)) {
            $newStream = fopen($newStream, '+r');
        }
        if (is_resource($newStream) === false) {
            throw new \InvalidArgumentException(__METHOD__ . ' argument must be a valid PHP resource');
        }

        if ($this->isAttached() === true) {
            $this->detach();
        }

        $this->stream = $newStream;
    }
}