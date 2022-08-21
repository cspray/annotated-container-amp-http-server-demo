<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Controller;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Dto;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\DtoHandler;
use Cspray\AnnotatedContainer\AutowireableInvoker;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use function Cspray\AnnotatedContainer\autowiredParams;
use function Cspray\AnnotatedContainer\rawParam;

abstract class DtoController implements RequestHandler {

    private readonly TreeMapper $mapper;

    public function __construct(
        private readonly AutowireableInvoker $invoker
    ) {
        $this->mapper = (new MapperBuilder())->mapper();
    }

    final public function handleRequest(Request $request) : Response {
        $reflection = new \ReflectionObject($this);
        foreach ($reflection->getMethods() as $reflectionMethod) {
            $dtoHandler = $reflectionMethod->getAttributes(DtoHandler::class);
            if ($dtoHandler !== []) {
                foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
                    $dto = $reflectionParameter->getAttributes(Dto::class);
                    if ($dto !== []) {
                        $json = json_decode($request->getBody()->buffer(), true);
                        $dto = $this->mapper->map($reflectionParameter->getType()->getName(), $json);

                        $callable = [$this, $reflectionMethod->getName()];

                        return $this->invoker->invoke($callable, autowiredParams(rawParam($reflectionParameter->getName(), $dto)));
                    }
                }
            }
        }

        return new Response(Status::INTERNAL_SERVER_ERROR, body: 'Could not find a DtoHandler on a DtoController');
    }

}