<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\AmpHttpServer;

use Amp\Http\Server\DefaultErrorHandler;
use Amp\Http\Server\HttpServer;
use Cspray\AnnotatedContainer\AmpHttpServer\Routing\RouteAutowiringListener;
use Cspray\AnnotatedContainer\AmpHttpServer\Routing\Router;
use Cspray\AnnotatedContainer\Bootstrap;
use Psr\Log\LoggerInterface;
use function Amp\trapSignal;
use function Cspray\AnnotatedContainer\eventEmitter;

require_once __DIR__ . '/vendor/autoload.php';

eventEmitter()->registerListener(new RouteAutowiringListener());

$container = (new Bootstrap())->bootstrapContainer();

$server = $container->get(HttpServer::class);

$server->start($container->get(Router::class), new DefaultErrorHandler());

$signal = trapSignal([\SIGINT, \SIGTERM]);

$container->get(LoggerInterface::class)->info(sprintf('Received signal %d, stopping HTTP server', $signal));

$server->stop();