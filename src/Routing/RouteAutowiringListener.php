<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Routing;

use Amp\Http\Server\RequestHandler;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Controller;
use Cspray\AnnotatedContainer\ServiceGatheringListener;
use function Cspray\Typiphy\objectType;

class RouteAutowiringListener extends ServiceGatheringListener {

    protected function doServiceGathering() : void {
        $router = iterator_to_array($this->getServicesOfType(objectType(Router::class)))[0];
        assert($router instanceof Router);
        foreach ($this->getServicesOfType(objectType(RequestHandler::class)) as $service) {
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