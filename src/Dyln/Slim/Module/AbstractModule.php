<?php

namespace Dyln\Slim\Module;

abstract class AbstractModule implements ModuleInterface
{
    protected $priority = 1000;

    public function init($params = [])
    {
    }

    public function boot()
    {
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
