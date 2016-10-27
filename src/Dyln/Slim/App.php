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
    protected $settings;

    public function __construct($settings, $base)
    {
        $this->settings = $settings;
        $containerBuilder = new ContainerBuilder(Container::class);
        $containerBuilder->addDefinitions($base);
//        $containerBuilder->setDefinitionCache(new PhpFileCache('/tmp'));
        $this->configureContainer($containerBuilder);
        $container = $containerBuilder->build();
        $container->set('app', $this);
        parent::__construct($container);
        $this->registerServiceProviders();
    }


    public function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions($this->settings);
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
        $providers = $this->getContainer()->get('config')['providers'];
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
}