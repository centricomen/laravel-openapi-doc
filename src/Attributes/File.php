<?php

namespace Vyuldashev\LaravelOpenApi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class File
{
    public array $name;

    public function __construct(array $name = [])
    {
        $this->name = $name;
    }
}