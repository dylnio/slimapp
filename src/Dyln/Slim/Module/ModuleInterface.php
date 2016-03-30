<?php
namespace Dyln\Slim\Module;

use Interop\Container\ContainerInterface;

interface ModuleInterface
{
    public function init(ContainerInterface $containerInterface);
}