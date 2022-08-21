<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Routing;

use Amp\Http\Server\RequestHandler;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Controller;
use Cspray\AnnotatedContainer\AnnotatedContainer;
use Cspray\AnnotatedContainer\Bootstrap\Observer;
use Cspray\AnnotatedContainer\ContainerDefinition;
use function Cspray\Typiphy\objectType;

class RouteAutowiring implements Observer {

    public function beforeCompilation() : void {
        // noop
    }

    public function afterCompilation(ContainerDefinition $containerDefinition) : void {
        // noop
    }

    public function beforeContainerCreation(ContainerDefinition $containerDefinition) : void {
        // noop
    }

    public function afterContainerCreation(ContainerDefinition $containerDefinition, AnnotatedContainer $container) : void {
        $router = $container->get(Router::class);
        assert($router instanceof Router);
        foreach ($containerDefinition->getServiceDefinitions() as $serviceDefinition) {
            if ($serviceDefinition->isAbstract() || !is_a($serviceDefinition->getType()->getName(), RequestHandler::class, true)){
                continue;
            }

            $service = $container->get($serviceDefinition->getType()->getName());
            // Let's make sure it has a Controller Attribute, so we can figure out the method/path to use.
            $reflection = new \ReflectionObject($service);
            $controllerAttr = $reflection->getAttributes(Controller::class)[0] ?? null;

            if ($controllerAttr === null) {
                continue;
            }

            /** @var Controller $controller */
            $controller = $controllerAttr->newInstance();

            $route = new Route(
                $controller->getMethod(),
                $controller->getPath(),
                $service
            );
            $router->addRoute($route);
        }
    }
}