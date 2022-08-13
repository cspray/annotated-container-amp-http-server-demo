<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer;

use Amp\Http\Server\HttpServer;
use Cspray\AnnotatedContainer\AmpHttpServer\Logger\LoggerFactory;
use Cspray\AnnotatedContainer\ContainerDefinitionBuilderContext;
use Cspray\AnnotatedContainer\ContainerDefinitionBuilderContextConsumer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use function Cspray\AnnotatedContainer\service;
use function Cspray\AnnotatedContainer\serviceDelegate;
use function Cspray\AnnotatedContainer\servicePrepare;
use function Cspray\Typiphy\objectType;

final class ThirdPartyServicesProvider implements ContainerDefinitionBuilderContextConsumer {

    public function consume(ContainerDefinitionBuilderContext $context) : void {
        // Setup Amp services
        service($context, objectType(HttpServer::class));

        // Setup logger injection
        service($context, $logger = objectType(LoggerInterface::class));
        serviceDelegate($context, $logger, objectType(LoggerFactory::class), 'createLogger');
        servicePrepare($context, objectType(LoggerAwareInterface::class), 'setLogger');
    }
}