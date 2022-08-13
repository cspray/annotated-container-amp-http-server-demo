<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Routing;

use Amp\Http\Server\RequestHandler;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Controller;
use Cspray\AnnotatedContainer\AnnotatedContainer;
use Cspray\AnnotatedContainer\AnnotatedContainerEvent;
use Cspray\AnnotatedContainer\AnnotatedContainerLifecycle;
use Cspray\AnnotatedContainer\AnnotatedContainerListener;
use Cspray\AnnotatedContainer\ContainerDefinition;

class RouteAutowiringListener implements AnnotatedContainerListener {

    private ?ContainerDefinition $containerDefinition = null;

    public function handle(AnnotatedContainerEvent $event) : void {
        if ($event->getLifecycle() === AnnotatedContainerLifecycle::BeforeContainerCreation) {
            $this->containerDefinition = $event->getTarget();
        } else if ($event->getLifecycle() === AnnotatedContainerLifecycle::AfterContainerCreation) {
            $container = $event->getTarget();
            assert($container instanceof AnnotatedContainer);
            $router = $container->get(Router::class);
            assert($router instanceof Router);
            foreach ($this->containerDefinition->getServiceDefinitions() as $serviceDefinition) {
                if ($serviceDefinition->isAbstract()) {
                    continue;
                }

                // We only wanna deal with RequestHandler that are NOT our Router
                $serviceType = $serviceDefinition->getType()->getName();
                if ($serviceType === Router::class || !is_a($serviceType, RequestHandler::class, true)) {
                    continue;
                }

                // Let's make sure it has a Controller Attribute, so we can figure out the method/path to use.
                $reflection = new \ReflectionClass($serviceType);
                $controllerAttr = $reflection->getAttributes(Controller::class)[0] ?? null;

                if ($controllerAttr === null) {
                    continue;
                }

                /** @var Controller $controller */
                $controller = $controllerAttr->newInstance();

                $route = new Route(
                    $controller->getMethod(),
                    $controller->getPath(),
                    $container->get($serviceType)
                );
                $router->addRoute($route);
            }
        }
    }
}