<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Http;

use Amp\Http\Server\HttpServer;
use Amp\Http\Server\SocketHttpServer;
use Amp\Socket\BindContext;
use Amp\Socket\Certificate;
use Amp\Socket\InternetAddress;
use Amp\Socket\ServerTlsContext;
use Cspray\AnnotatedContainer\AmpHttpServer\Configuration\HttpServerConfiguration;
use Cspray\AnnotatedContainer\Attribute\ServiceDelegate;
use Psr\Log\LoggerInterface;

/**
 * @psalm-import-type InternetAddressConfig from HttpServerConfiguration
 */
final class HttpServerFactory {

    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    #[ServiceDelegate]
    public function createServer(HttpServerConfiguration $configuration) : HttpServer {
        if (!file_exists($configuration->sslCertificatePath)) {
            $this->logger->error(sprintf('Failed to find a SSL certificate at %s', $configuration->sslCertificatePath));
            throw new \RuntimeException();
        }
        $cert = new Certificate($configuration->sslCertificatePath);
        $sslContext = (new BindContext())->withTlsContext(
            (new ServerTlsContext())->withDefaultCertificate($cert)
        );

        $server = new SocketHttpServer($this->logger);

        /** @var array{ipAddress: string, port: int, ssl?: bool} $internetAddress */
        foreach ($configuration->internetAddresses as $internetAddress) {
            $ssl = $internetAddress['ssl'] ?? false;
            if ($ssl) {
                $server->expose(new InternetAddress($internetAddress['ipAddress'], $internetAddress['port']), $sslContext);
            } else {
                $server->expose(new InternetAddress($internetAddress['ipAddress'], $internetAddress['port']));
            }
        }

        return $server;
    }

}