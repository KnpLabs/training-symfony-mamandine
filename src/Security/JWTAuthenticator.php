<?php

namespace App\Security;

use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTAuthenticator extends AbstractGuardAuthenticator
{
    private Encoder $encoder;

    public function __construct(Encoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        if (!$authHeader = $request->headers->get('Authorization')) {
            return false;
        }

        return (bool) preg_match('/^bearer /i', $authHeader);
    }

    public function getCredentials(Request $request)
    {
        return preg_replace(
            '/^bearer /i',
            '',
            $request->headers->get('Authorization', '')
        );
    }

    /**
     * @param mixed $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        try {
            $token = $this->encoder->decode($credentials);
        } catch (Exception $e) {
            return null;
        }

        if ($token->isExpired(new DateTime())) {
            return null;
        }

        if (!($username = UsernameResolver::resolveUsername($token))) {
            return null;
        }

        return $userProvider->loadUserByUsername($username);
    }

    /**
     * @param mixed $credentials
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(null, 401);
    }

    public function start(Request $request, AuthenticationException $authException = null): ?Response
    {
        return new JsonResponse(null, 401);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
