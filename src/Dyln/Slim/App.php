<?php

namespace Dyln\Slim;

use DI\ContainerBuilder;
use Dyln\DI\Container;
use Dyln\Slim\Module\ModuleInterface;
use Dyln\Slim\ServiceProvider\BootableServiceProviderInterface;
use Dyln\Slim\ServiceProvider\ServiceProviderInterface;
use Interop\Container\ContainerInterface;

class App extends \Slim\App
{
    /** @var ModuleInterface[] */
    protected $modules = [];
    protected $config;

    public function __construct($config, $params = [])
    {
        $this->config = $config;
        $containerBuilder = new ContainerBuilder(Container::class);
        $containerBuilder->setDefinitionCache(new \Doctrine\Common\Cache\ArrayCache($params['di']['cache']['dir']));
        $containerBuilder->addDefinitions($this->config);
        $container = $containerBuilder->build();
        $container->set('app', $this);
        $container->set(App::class, $this);
        $container->set('app_params', $params);
        parent::__construct($container);
        $this->registerModules($container);
    }

    public function boot($params = [])
    {
        foreach ($this->modules as $module) {
            $module->init($params);
            $module->boot();
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

    private function registerModules(ContainerInterface $container)
    {
        $modules = $container->get('app_params')['modules'];
        foreach ($modules as $moduleClass) {
            /** @var ModuleInterface $module */
            $module = $container->get($moduleClass);
            $this->modules[] = $module;
        }
    }
}
