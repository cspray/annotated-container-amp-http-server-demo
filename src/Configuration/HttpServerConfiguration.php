<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Configuration;

use Cspray\AnnotatedContainer\Attribute\Configuration;
use Cspray\AnnotatedContainer\Attribute\Inject;

#[Configuration]
final class HttpServerConfiguration {

    /**
     * Though it isn't necessary for this app you could use the profiles functionality to define different IP
     * addresses for dev vs testing vs staging vs prod.
     *
     * @var list<array{
     *     ipAddress: string,
     *     port: int,
     *     ssl?: bool
     * }>
     */
    #[Inject([
        ['ipAddress' => '0.0.0.0', 'port' => 1337],
        ['ipAddress' => '[::]', 'port' => 1337],
        ['ipAddress' => '0.0.0.0', 'port' => 1338, 'ssl' => true],
        ['ipAddress' => '[::]', 'port' => 1338, 'ssl' => true]
    ])]
    public readonly array $internetAddresses;

    #[Inject(__DIR__ . '/../../resources/certificates/server.pem')]
    public readonly string $sslCertificatePath;

}