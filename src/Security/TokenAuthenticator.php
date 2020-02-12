<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;

final class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $apiToken;

    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('Auth header required', 401);
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer');
    }

    public function getCredentials(Request $request)
    {
        $auth = $request->headers->get('Authorization');
        list($_, $token) = array_map('trim', explode(' ', $auth));

        return ['token' => $token];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return new class() implements UserInterface {
            public function getRoles()
            {
                return [];
            }

            public function getPassword()
            {
                return null;
            }

            public function getSalt()
            {
                return null;
            }

            public function getUsername()
            {
                return 'api';
            }

            public function eraseCredentials()
            {
            }
        };
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->apiToken === $credentials['token'];
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response('', 403);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
