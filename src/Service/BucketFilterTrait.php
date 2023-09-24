<?php

declare(strict_types=1);

namespace App\Service;

trait BucketFilterTrait
{
    public const BUCKET_LOW = 0;
    public const BUCKET_MEDIUM = 1;
    public const BUCKET_HIGH = 2;

    protected function sanitizeValues(array $values): array
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($values as $key => $value) {
            if (is_int($value) || false !== filter_var($value, FILTER_VALIDATE_INT)) {
                $values[$key] = (int) $value;
            } elseif (is_float($value) || false !== filter_var($value, FILTER_VALIDATE_FLOAT)) {
                $values[$key] = (float) $value;
            } else {
                /* @psalm-suppress MixedArgument */
                throw new \InvalidArgumentException(sprintf('Can not filter value with key "%s", must be of type int or float', $key));
            }
        }

        return $values;
    }
}
