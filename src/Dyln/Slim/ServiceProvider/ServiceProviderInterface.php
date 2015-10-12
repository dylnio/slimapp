<?php
namespace Dyln\ServiceProvider;

use Interop\Container\ContainerInterface;

interface ServiceProviderInterface
{
    public function register(ContainerInterface $container);
}