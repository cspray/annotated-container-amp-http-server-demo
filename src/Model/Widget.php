<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Model;

class Widget {

    public function __construct(
        public readonly string $type,
        public readonly string $name,
        public readonly int $counter,
        public readonly Author $author
    ) {}

}