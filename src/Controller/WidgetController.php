<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Controller;

use Amp\Http\Server\Response;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Controller;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\Dto;
use Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion\DtoHandler;
use Cspray\AnnotatedContainer\AmpHttpServer\Http\HttpMethod;
use Cspray\AnnotatedContainer\AmpHttpServer\Model\Widget;

#[Controller(HttpMethod::Post, '/widget')]
class WidgetController extends DtoController {

    #[DtoHandler]
    public function createWidget(#[Dto] Widget $widget) : Response {
        $message = sprintf(
            'We received a Widget named %s of type %s. Authored by %s <%s>.',
            $widget->name,
            $widget->type,
            $widget->author->name,
            $widget->author->email
        );
        return new Response(headers: ['Content-Type' => 'text/plain'], body: $message);
    }



}