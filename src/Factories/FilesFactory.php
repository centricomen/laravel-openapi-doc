<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use Vyuldashev\LaravelOpenApi\Concerns\Referencable;

abstract  class FilesFactory
{
    use Referencable;

    abstract public function build();
}