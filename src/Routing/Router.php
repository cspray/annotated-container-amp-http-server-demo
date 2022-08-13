<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Routing;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Cspray\AnnotatedContainer\Attribute\Service;

#[Service]
final class Router implements RequestHandler {

    /**
     * @var list<Route>
     */
    private array $routes = [];

    public function addRoute(Route $route) : void {
        $this->routes[] = $route;
    }

    public function handleRequest(Request $request) : Response {
        foreach ($this->routes as $route) {
            $requestMethod = strtolower($request->getMethod());
            $routeMethod = strtolower($route->method->value);
            if ($requestMethod !== $routeMethod) {
                continue;
            }

            if ($request->getUri()->getPath() === $route->path) {
                return $route->handler->handleRequest($request);
            }
        }

        return new Response(Status::NOT_FOUND, body: 'Not Found');
    }
}