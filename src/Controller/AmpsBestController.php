<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Controller;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Controller;
use Cspray\AnnotatedContainer\AmpHttpServer\Http\HttpMethod;

#[Controller(method: HttpMethod::Get, path: '/amp')]
final class AmpsBestController implements RequestHandler {

    public function handleRequest(Request $request) : Response {
        return new Response(Status::OK, body: 'Amp is the best. Even better with Annotated Container!');
    }
}