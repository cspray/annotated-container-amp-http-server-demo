<?php

namespace Cspray\AnnotatedContainer\AmpHttpServer\Model;

class Author {

    public function __construct(
        public readonly string $name,
        public readonly string $email
    ) {
    }


}