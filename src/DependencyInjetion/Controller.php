<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\DependencyInjetion;

use Attribute;
use Cspray\AnnotatedContainer\AmpHttpServer\Http\HttpMethod;
use Cspray\AnnotatedContainer\Attribute\ServiceAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Controller implements ServiceAttribute {

    public function __construct(
        private readonly HttpMethod $method,
        private readonly string $path,
        private readonly array $profiles = []
    ) {}

    public function getMethod() : HttpMethod {
        return $this->method;
    }

    public function getPath() : string {
        return $this->path;
    }

    public function getProfiles() : array {
        return $this->profiles;
    }

    public function isPrimary() : bool {
        return false;
    }

    public function getName() : ?string {
        return null;
    }

}