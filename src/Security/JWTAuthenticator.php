<?php

namespace App\Security;

use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JWTAuthenticator extends AbstractAuthenticator
{
    private Encoder $encoder;
    private UserProviderInterface $userProvider;

    public function __construct(Encoder $encoder, UserProviderInterface $userProvider)
    {
        $this->encoder = $encoder;
        $this->userProvider = $userProvider;
    }

    public function supports(Request $request): bool
    {
        if (!$authHeader = $request->headers->get('Authorization')) {
            return false;
        }

        return (bool) preg_match('/^bearer /i', $authHeader);
    }

    public function authenticate(Request $request): Passport
    {
        $token = $this->resolveTokenFromRequest($request);

        if (null === $token) {
            throw new Exception('No API token provided.');
        }

        try {
            $decodedToken = $this->encoder->decode($token);
        } catch (Exception $e) {
            throw new Exception('The API token is invalid.');
        }

        if ($decodedToken->isExpired(new DateTime())) {
            throw new Exception('The API token is expired.', 401);
        }

        if (!($username = UsernameResolver::resolveUsername($decodedToken))) {
            throw new Exception('The API token is expired.', 401);
        }

        return new SelfValidatingPassport(new UserBadge($username, function ($userIdentifier) {
            return $this->userProvider->loadUserByIdentifier($userIdentifier);
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(null, 401);
    }

    private function resolveTokenFromRequest(Request $request): ?string
    {
        return preg_replace(
            '/^bearer /i',
            '',
            $request->headers->get('Authorization', '')
        );
    }
}
