<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Logger;

use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Cspray\AnnotatedContainer\Attribute\ServiceDelegate;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerInterface;
use function Amp\ByteStream\getStdout;

final class LoggerFactory {

    #[ServiceDelegate]
    public function createLogger() : LoggerInterface {
        $logHandler = new StreamHandler(getStdout());
        $logHandler->pushProcessor(new PsrLogMessageProcessor());
        $logHandler->setFormatter(new ConsoleFormatter());
        $logger = new Logger('annotated-container-server');
        $logger->pushHandler($logHandler);

        return $logger;
    }

}