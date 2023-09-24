<?php

declare(strict_types=1);

namespace App\Tests\unit;

trait BucketFilterTestTrait
{
    public function providerValidValues(): array
    {
        return [
            // All integers
            [[1, 2, 3, 4, 5, 6]],
            // All floats
            [[1.1, 2.2, 3.3, 4.4, 5.5, 6.6]],
            // Mix of integers and floats
            [[1, 2.2, 3, 4.4, 5, 6.6]],
            // Mix of integers (as strings) and floats
            [['1', 2.2, '3', 4.4, '5', 6.6]],
            // Mix of integers and floats (as strings)
            [[1, '2.2', 3, '4.4', 5, '6.6']],
        ];
    }

    public function providerInvalidValues(): array
    {
        return [
            [[[], new \stdClass(), 'foo']],
        ];
    }
}
