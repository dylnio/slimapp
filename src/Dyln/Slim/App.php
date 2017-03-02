<?php

namespace Dyln\Slim;

use DI\ContainerBuilder;
use Dyln\DI\Container;
use Dyln\Slim\ServiceProvider\BootableServiceProviderInterface;
use Dyln\Slim\ServiceProvider\ServiceProviderInterface;

class App extends \Slim\App
{
    /** @var ServiceProviderInterface[] */
    protected $providers = [];
    protected $config;

    public function __construct($config, $params = [])
    {
        $this->config = $config;
        $containerBuilder = new ContainerBuilder(Container::class);
//        $containerBuilder->addDefinitions($config);
        $this->configureContainer($containerBuilder);
        $container = $containerBuilder->build();
        $container->set('app', $this);
        $container->set('app_params', $params);
        parent::__construct($container);
        $this->registerServiceProviders();
    }


    public function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions($this->config);
    }

    public function addServiceProvider(ServiceProviderInterface $provider)
    {
        $this->providers[get_class($provider)] = $provider;
    }

    public function addServiceProviders($providers = [])
    {
        foreach ($providers as $provider) {
            $this->addServiceProvider($provider);
        }
    }

    public function boot()
    {
        foreach ($this->providers as $provider) {
            $provider->register($this->getContainer());
        }
        foreach ($this->providers as $provider) {
            if ($provider instanceof BootableServiceProviderInterface) {
                $provider->boot($this->getContainer());
            }
        }
    }

    private function registerServiceProviders()
    {
        $providers = $this->getContainer()->get('app_params')['providers'];
        foreach ($providers as $provider) {
            $this->addServiceProvider($provider);
        }
    }

    public function getGeneric($pattern, $actionClassName)
    {
        return $this->get($pattern, $actionClassName . ':dispatch');
    }

    public function postGeneric($pattern, $actionClassName)
    {
        return $this->post($pattern, $actionClassName . ':dispatch');
    }

    public function putGeneric($pattern, $actionClassName)
    {
        return $this->put($pattern, $actionClassName . ':dispatch');
    }
}