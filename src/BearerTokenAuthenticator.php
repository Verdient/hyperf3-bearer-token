<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\BearerToken;

use Exception;
use Hyperf\Contract\ConfigInterface;
use Override;
use Psr\Http\Message\ServerRequestInterface;
use Verdient\Hyperf3\AccessControl\AbstractAuthenticator;
use Verdient\Hyperf3\AccessControl\Identity;
use Verdient\Hyperf3\AccessControl\IdentityInterface;
use Verdient\token\Token;

/**
 * 访问令牌认证器
 *
 * @author Verdient。
 */
class BearerTokenAuthenticator extends AbstractAuthenticator
{
    /**
     * 配置集合
     *
     * @author Verdient。
     */
    protected array $configs = [];

    /**
     * @param ConfigInterface $config 配置信息
     *
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
    public function identity(ServerRequestInterface $request, string $group): ?IdentityInterface
    {
        if (!$token = $this->getToken($request)) {
            return null;
        }

        $parts = explode(' ', $token);

        if (strtolower($parts[0]) === 'bearer') {
            array_shift($parts);
        }

        $token = implode(' ', $parts);

        if (!$identifier = $this->parseToken($token, $group)) {
            return null;
        }

        return new Identity($identifier);
    }

    /**
     * 解析令牌
     *
     * @param string $token 令牌
     * @param string $group 分组
     *
     * @author Verdient。
     */
    protected function parseToken(string $token, string $group): int|string|false
    {
        if (empty($this->configs[$group])) {
            throw new Exception('Unconfigured bearer token group: ' . $group);
        }
        try {
            return (new Token($this->configs[$group]))
                ->parse($token);
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * 获取令牌
     *
     * @param ServerRequestInterface $request 请求
     *
     * @author Verdient。
     */
    public function getToken(ServerRequestInterface $request): ?string
    {
        if ($token = $request->getHeaderLine('authorization')) {
            return $token;
        }

        $queryParams = array_change_key_case($request->getQueryParams(), CASE_LOWER);

        if (!empty($queryParams['authorization'])) {
            return $queryParams['authorization'];
        }

        return null;
    }
}
