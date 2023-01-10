<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Support\Arr;
use Vyuldashev\LaravelOpenApi\Builders\ComponentsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\InfoBuilder;
use Vyuldashev\LaravelOpenApi\Builders\PathsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\ServersBuilder;
use Vyuldashev\LaravelOpenApi\Builders\TagsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\FilesBuilder;

class Generator
{
    public string $version = OpenApi::OPENAPI_3_0_2;

    public const COLLECTION_DEFAULT = 'default';

    protected array $config;
    protected InfoBuilder $infoBuilder;
    protected ServersBuilder $serversBuilder;
    protected TagsBuilder $tagsBuilder;
    protected PathsBuilder $pathsBuilder;
    protected ComponentsBuilder $componentsBuilder;

    protected FilesBuilder $filesBuilder;

    public function __construct(
        array             $config,
        InfoBuilder       $infoBuilder,
        ServersBuilder    $serversBuilder,
        TagsBuilder       $tagsBuilder,
        PathsBuilder      $pathsBuilder,
        ComponentsBuilder $componentsBuilder,
        FilesBuilder      $filesBuilder = null
    )
    {
        $this->config = $config;
        $this->infoBuilder = $infoBuilder;
        $this->serversBuilder = $serversBuilder;
        $this->tagsBuilder = $tagsBuilder;
        $this->pathsBuilder = $pathsBuilder;
        $this->componentsBuilder = $componentsBuilder;
        $this->filesBuilder = $filesBuilder;
    }

    public function generate(string $collection = self::COLLECTION_DEFAULT): array
    {
        $middlewares = Arr::get($this->config, 'collections.' . $collection . '.middlewares');

        $files = $this->filesBuilder->build(Arr::get($this->config, 'collections.' . $collection . '.files', []));
        $apiDocs = array();
        if (is_array($files)) {
            foreach ($files as $file) {
                $info = $this->infoBuilder->build(Arr::get($this->config, 'collections.' . $collection . '.info', []));
                $servers = $this->serversBuilder->build(Arr::get($this->config, 'collections.' . $collection . '.servers', []));
                $tags = $this->tagsBuilder->build(Arr::get($this->config, 'collections.' . $collection . '.tags', []));
                $paths = $this->pathsBuilder->build($collection, Arr::get($middlewares, 'paths', []));
                $components = $this->componentsBuilder->build($collection, Arr::get($middlewares, 'components', []));
                $extensions = Arr::get($this->config, 'collections.' . $collection . '.extensions', []);

                sort($paths);

                $openApi = OpenApi::create()
                    ->openapi('2.0')
                    ->info($info)
                    ->servers(...$servers)
                    ->paths(...$paths)
                    ->components($components)
                    ->security(...Arr::get($this->config, 'collections.' . $collection . '.security', []))
                    ->tags(...$tags);

                $apiDocs[$file] = $openApi;

                foreach ($extensions as $key => $value) {
                    $openApi = $openApi->x($key, $value);
                }
            }
        }

        return $apiDocs;
    }
}
