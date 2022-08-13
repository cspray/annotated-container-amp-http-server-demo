<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Controller;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Controller;
use Cspray\AnnotatedContainer\AmpHttpServer\Http\HttpMethod;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

#[Controller(method: HttpMethod::Get, path: '/')]
final class HelloWorldController implements RequestHandler, LoggerAwareInterface {

    private LoggerInterface $logger;

    public function __construct() {
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger) : void {
        $this->logger = $logger;
    }

    public function handleRequest(Request $request) : Response {
        $this->logger->info('Calling ' . self::class);
        return new Response(Status::OK, [
            'content-type' => 'text/plain; charset=utf-8'
        ], 'Hello, World!');
    }

}