<?php

declare(strict_types=1);

namespace App\Tests\unit;

use App\Service\TriBucketFilterEqualFrequency;
use PHPUnit\Framework\TestCase;

class TriBucketFilterEqualFrequencyTest extends TestCase
{
    use BucketFilterTestTrait;

    private TriBucketFilterEqualFrequency $filter;

    protected function setUp(): void
    {
        $this->filter = new TriBucketFilterEqualFrequency();
    }

    /**
     * @dataProvider providerValidValues
     */
    public function testFilterValidValues(array $values): void
    {
        $output = $this->filter->filter($values);

        // We are only asserting to check for the absence of an exception.
        $this->assertNotEmpty($output);
    }

    /**
     * @dataProvider providerInvalidValues
     */
    public function testFilterInvalidValues(array $values): void
    {
        $this->expectExceptionMessageMatches('/can not filter value/i');

        $this->filter->filter($values);
    }

    /**
     * @dataProvider providerInvalidValuesLength
     */
    public function testFilterRequiresValuesLengthMultipleOfThree(array $values): void
    {
        $this->expectExceptionMessageMatches('/invalid value count/i');

        $this->filter->filter($values);
    }

    public function testFilterSuccess(): void
    {
        $values = [0.1, 3.4, 3.5, 3.6, 7.0, 9.0, 6.0, 4.4, 2.5, 3.9, 4.5, 2.8];

        $output = $this->filter->filter($values);

        $this->assertCount(3, $output);
        $this->assertEquals([0.1, 3.4, 2.5, 2.8], $output[TriBucketFilterEqualFrequency::BUCKET_LOW]);
        $this->assertEquals([3.5, 3.6, 4.4, 3.9], $output[TriBucketFilterEqualFrequency::BUCKET_MEDIUM]);
        $this->assertEquals([7, 9, 6, 4.5], $output[TriBucketFilterEqualFrequency::BUCKET_HIGH]);
    }

    public function providerInvalidValuesLength(): array
    {
        return [
            [[]],
            [[1]],
            [[1, 2]],
            [[1, 2, 3, 4]],
            [[1, 2, 3, 4, 5]],
            [[1, 2, 3, 4, 5, 6, 7]],
        ];
    }
}
