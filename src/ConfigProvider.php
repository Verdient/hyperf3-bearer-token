<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\BearerToken;

use Verdient\Hyperf3\AccessControl\AuthenticatorInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AuthenticatorInterface::class => BearerTokenAuthenticator::class
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for bearer token.',
                    'source' => dirname(__DIR__) . '/publish/bearer_token.php',
                    'destination' => constant('BASE_PATH') . '/config/autoload/bearer_token.php',
                ]
            ]
        ];
    }
}
