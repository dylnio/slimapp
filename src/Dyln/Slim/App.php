<?php
namespace Dyln\Slim;

use Dyln\ServiceProvider\ServiceProviderInterface;

class App extends \Slim\App
{
    /** @var ServiceProviderInterface[] */
    protected $providers = [];

    public function __construct($container = [])
    {
        parent::__construct($container);
        $this->registerServiceProviders();
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
    }

    private function registerServiceProviders()
    {
        $providers = $this->getContainer()->get('settings')['providers'];
        foreach ($providers as $provider) {
            $this->addServiceProvider($provider);
        }
    }
}