<?php
namespace App\Security;

use InvalidArgumentException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256 as Rs256;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class Encoder
{
    private Configuration $configuration;

    /**
     * @param string $privateKey the private RSA key content
     * @param string $publicKey  the public RSA key content
     */
    public function __construct(string $privateKey, string $publicKey)
    {
        $this->configuration = Configuration::forAsymmetricSigner(
            new Rs256(),
            InMemory::plainText($privateKey),
            InMemory::plainText($publicKey),
        );
    }

    public function encode(JWT $token): string
    {
        $tokenBuilder = $this->configuration
            ->builder()
            ->issuedAt($token->getIssuedAt())
            ->expiresAt($token->getExpireAt())
        ;

        foreach ($token->getData() as $key => $value) {
            $tokenBuilder->withClaim($key, $value);
        }

        return $tokenBuilder->getToken(
            $this->configuration->signer(),
            $this->configuration->signingKey()
        )->toString();
    }

    public function decode(string $rawToken): Plain
    {
        $this->configuration->setValidationConstraints(new SignedWith(
            $this->configuration->signer(),
            $this->configuration->verificationKey()
        ));

        $token = $this->configuration->parser()->parse($rawToken);
        $constraints = $this->configuration->validationConstraints();

        if (!$token instanceof Plain || !$this->configuration->validator()->validate($token, ...$constraints)) {
            throw new InvalidArgumentException('Invalid token.');
        }

        return $token;
    }
}
