<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\BearerToken;

use Verdient\Hyperf3\AccessControl\AuthenticatorInterface;
use Verdient\Hyperf3\AccessControl\TokenGeneratorInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AuthenticatorInterface::class => BearerTokenAuthenticator::class,
                TokenGeneratorInterface::class => BearerTokenGenerator::class
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
