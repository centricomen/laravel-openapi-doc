<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use Illuminate\Support\Arr;

class FilesBuilder
{
    public function build(array $config): array
    {
        return $config[ 'files' ];
    }
}
