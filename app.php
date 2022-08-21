<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\AmpHttpServer;

use Amp\Http\Server\DefaultErrorHandler;
use Amp\Http\Server\HttpServer;
use Cspray\AnnotatedContainer\AmpHttpServer\Routing\Router;
use Cspray\AnnotatedContainer\Bootstrap;
use Psr\Log\LoggerInterface;
use function Amp\trapSignal;

require_once __DIR__ . '/vendor/autoload.php';

$container = (new Bootstrap())->bootstrapContainer();

$server = $container->get(HttpServer::class);

$server->start($container->get(Router::class), new DefaultErrorHandler());

$signal = trapSignal([\SIGINT, \SIGTERM]);

$container->get(LoggerInterface::class)->info(sprintf('Received signal %d, stopping HTTP server', $signal));

$server->stop();