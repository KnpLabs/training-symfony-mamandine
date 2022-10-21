<?php

namespace App\Security;

use DateTimeImmutable;

interface JWT
{
    public function getIssuedAt(): DateTimeImmutable;

    public function getExpireAt(): DateTimeImmutable;

    public function getData(): array;
}
