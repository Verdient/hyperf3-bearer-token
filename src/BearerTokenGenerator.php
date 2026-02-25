<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\BearerToken;

use Exception;
use Hyperf\Contract\ConfigInterface;
use Override;
use Verdient\Hyperf3\AccessControl\Token as AccessControlToken;
use Verdient\Hyperf3\AccessControl\TokenGeneratorInterface;
use Verdient\Hyperf3\AccessControl\TokenInterface;
use Verdient\token\Token;

/**
 * 访问令牌生成器
 *
 * @author Verdient。
 */
class BearerTokenGenerator implements TokenGeneratorInterface
{
    /**
     * 配置集合
     *
     * @author Verdient。
     */
    protected array $configs = [];

    /**
     * @param ConfigInterface $config 配置信息
     * @author Verdient。
     */
    public function __construct(protected ConfigInterface $config)
    {
        $this->configs = $this->config->get('bearer_token', []);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function generate(int|string $identifier, string $group = 'default'): TokenInterface
    {
        if (empty($group)) {
            $group = 'default';
        }

        if (empty($this->configs[$group])) {
            throw new Exception('Unconfigured bearer token group: ' . $group);
        }

        $token = (new Token($this->configs[$group]));

        return new AccessControlToken($token->generate($identifier), $token->duration + time());
    }
}
