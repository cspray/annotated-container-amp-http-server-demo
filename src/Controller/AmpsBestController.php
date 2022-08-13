<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Controller;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Controller;
use Cspray\AnnotatedContainer\AmpHttpServer\Http\HttpMethod;
use Psr\Log\LoggerInterface;

#[Controller(method: HttpMethod::Get, path: '/amp')]
final class AmpsBestController implements RequestHandler {

    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function handleRequest(Request $request) : Response {
        $this->logger->info('Calling ' . self::class);
        return new Response(Status::OK, [
            'content-type' => 'text/plain; charset=utf-8'
        ], 'Amp is the best. Even better with Annotated Container!');
    }
}