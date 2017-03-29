<?php
namespace Dyln\Slim\Module;

interface ModuleInterface
{
    public function init($params = []);

    public function boot();

    public function getPriority(): int;
}
