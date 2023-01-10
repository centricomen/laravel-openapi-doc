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

        if (!$collectionExists) {
            $this->error('Collection "' . $this->argument('collection') . '" does not exist.');

            return;
        }

        $data = $generator
            ->generate($this->argument('collection'));

        # $this->line( $data );
        $this->info('Generating documentation files...');

        foreach ($data as $file => $apiDocumentation) {

            $apiDocumentation = $apiDocumentation
                ->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $this->info('Writing for ' . $file);

            # First write to a public disk.
            Storage::disk('public')->put('/openapi/' . $file, $apiDocumentation);
            $this->info('Generated JSON file on storage disk storage/public/openapi');

            # Secondly write to a publicly accessible folder
            $basePath = base_path();
            $filePath = $basePath . '/public/storage/openapi/' . $file;

            if (!file_exists($basePath . '/public'))
                mkdir($basePath . '/public');

            if (!file_exists($basePath . '/public/storage'))
                mkdir($basePath . '/public/storage');

            if (!file_exists($basePath . '/public/storage/openapi'))
                mkdir($basePath . '/public/storage/openapi');

            $handle = fopen($filePath, 'w');
            fwrite($handle, $apiDocumentation);
            fclose($handle);
            $this->info('Generated JSON file on public path ' . $filePath);

        }
    }
}
