<?php
namespace Dyln\Slim\Module;

interface ModuleInterface
{
    public function init();

    public function boot();

    public function getPriority(): int;
}