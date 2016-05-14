<?php
namespace Dyln\Slim\Module;

use Interop\Container\ContainerInterface;

abstract class AbstractModule implements ModuleInterface
{
    /** @var  ContainerInterface */
    protected $container;

    /**
     * AbstractModule constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    abstract public function init();
}