<?php

namespace App\Security;

use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserTokenGenerator
{
    private UserProviderInterface $userProvider;
    private EncoderFactoryInterface $encoderFactory;
    private Encoder $encoder;

    public function __construct(
        UserProviderInterface $userProvider,
        EncoderFactoryInterface $encoderFactory,
        Encoder $encoder
    ) {
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
        $this->encoder = $encoder;
    }

    public function support(Request $request): bool
    {
        try {
            $username = $this->resolveUsernameFromRequest($request);
            $this->userProvider->loadUserByUsername($username);

            return true;
        } catch (UsernameNotFoundException $e) {
            return false;
        }
    }

    public function create(Request $request): string
    {
        try {
            $username = $this->getUsername($request);
            $user = $this->userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            throw new Exception('Bad credentials.', 401);
        }

        $passwordEncoder = $this->encoderFactory->getEncoder($user);
        $userPassword = $user->getPassword() ?? '';

        if (!$passwordEncoder->isPasswordValid(
            $userPassword,
            $this->getPassword($request),
            $user->getSalt()
        )) {
            throw new Exception('Bad credentials.', 401);
        }

        return $this->createToken($user);
    }

    public function refresh(Request $request): string
    {
        try {
            $username = $this->getUsernameFromToken($request);
            $user = $this->userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException|InvalidArgumentException $e) {
            // can be an invalid argument exception thrown during JWT decode
            throw new Exception('Invalid Token.', 401);
        }

        return $this->createToken($user);
    }

    private function resolveUsernameFromRequest(Request $request): string
    {
        // Check if request specifies a username
        try {
            $username = $this->getUsername($request);

            return $username;
        } catch(Exception $exception) {}

        // Check if the request specifies a token from which we can extract the
        // username
        try {
            $username = $this->getUsernameFromToken($request);

            return $username;
        } catch(Exception $exception) {}

        throw new Exception('Invalid request', 400);
    }

    protected function createToken(UserInterface $user): string
    {
        return $this->encoder->encode(
            new AuthenticationToken($user)
        );
    }

    protected function getUsernameFromToken(Request $request): string
    {
        $token = $request->request->get('token');

        if (!$token) {
            throw new Exception('You must specify a token.', 400);
        }

        $username = UsernameResolver::resolveUsername(
            $this->encoder->decode($token)
        );

        if (!$username) {
            throw new Exception('Invalid token.', 400);
        }

        return $username;
    }

    protected function getUsername(Request $request): string
    {
        if (!$request->request->has('username')) {
            throw new Exception('You must specify a "username".', 400);
        }

        return $request->request->get('username');
    }

    protected function getPassword(Request $request): string
    {
        if (!$request->request->has('password')) {
            throw new Exception('You must specify a "password".', 400);
        }

        return $request->request->get('password');
    }
}
