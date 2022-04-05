<?php

namespace App\Security;

use DateTimeImmutable;

class AuthenticationToken implements JWT
{
    private array $data;
    private \DateTimeImmutable $issuedAt;
    private \DateTimeImmutable $expireAt;

    public function __construct($data, int $duration = 3600)
    {
        $this->data = [
            'username' => $data->getUserIdentifier(),
            'email' => $data->getEmail()
        ];

        $this->issuedAt = new DateTimeImmutable('@'.time());
        $this->expireAt = (new DateTimeImmutable('@'.time()))->modify(sprintf('+%s seconds', $duration));
    }

    public function getIssuedAt(): DateTimeImmutable
    {
        return $this->issuedAt;
    }

    public function getExpireAt(): DateTimeImmutable
    {
        return $this->expireAt;
    }

    public function getData(): array
    {
        // turn the user definition into an array
        return json_decode(json_encode($this->data, \JSON_THROW_ON_ERROR), true);
    }
}
