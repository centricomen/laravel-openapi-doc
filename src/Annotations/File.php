<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class File
{
    /** @var string */
    public $name;
}
