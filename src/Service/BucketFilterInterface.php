<?php

declare(strict_types=1);

namespace App\Service;

interface BucketFilterInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @throws \InvalidArgumentException
     */
    public function filter(array $values): array;
}
