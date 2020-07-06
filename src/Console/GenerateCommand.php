<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Vyuldashev\LaravelOpenApi\Generator;

class GenerateCommand extends Command
{
    protected $signature = 'openapi:generate {collection=default}';
    protected $description = 'Generate OpenAPI specification';

    public function handle(Generator $generator): void
    {
        $collectionExists = collect(config('openapi.collections'))->has($this->argument('collection'));

        if (! $collectionExists) {
            $this->error('Collection "'.$this->argument('collection').'" does not exist.');

            return;
        }

        $data = $generator
            ->generate($this->argument('collection'))
            ->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

        # $this->line( $data );

        Storage::disk(  'public' ) -> put( '/openapi/openapi.json', $data );

        $this -> info( 'Generated JSON file on storage/public/openapi folder' );
    }
}
