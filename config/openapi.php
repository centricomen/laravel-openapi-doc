<?php

return [

    'collections' => [

        'default' => [

            'info' => [
                'title' => config('app.name'),
                'description' => null,
                'version' => '1.0.0',
                'x-logo' => array(
                    'url' => null
                )
            ],

            'files' => [
                // Include the files you want to render to here. "openapi.json" is the default. DO NOT REMOVE IT
                'openapi.json'
            ],

            'servers' => [
                [
                    'url' => env('APP_URL'),
                ],
            ],

            'tags' => [

                // [
                //    'name' => 'user',
                //    'description' => 'Application users',
                // ],

            ],

            'security' => [
                // GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement::create()->securityScheme('JWT'),
            ],

            // Route for exposing specification.
            // Leave uri null to disable.
            'route' => [
                'uri' => '/openapi',
                'middleware' => [],
            ],

            // Register custom middlewares for different objects.
            'middlewares' => [
                'paths' => [
                    //
                ],
            ],

        ],

    ],

];
