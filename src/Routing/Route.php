<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Routing;

use Amp\Http\Server\RequestHandler;
use Cspray\AnnotatedContainer\AmpHttpServer\Http\HttpMethod;

final class Route {

    public function __construct(
        public readonly HttpMethod $method,
        public readonly string $path,
        public readonly RequestHandler $handler
    ) {}

}