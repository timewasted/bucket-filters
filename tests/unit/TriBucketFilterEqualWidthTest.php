<?php

declare(strict_types=1);

namespace App\Tests\unit;

use App\Service\TriBucketFilterEqualWidth;
use PHPUnit\Framework\TestCase;

class TriBucketFilterEqualWidthTest extends TestCase
{
    use BucketFilterTestTrait;

    private TriBucketFilterEqualWidth $filter;

    protected function setUp(): void
    {
        $this->filter = new TriBucketFilterEqualWidth(0, 4);
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
     * @dataProvider providerInputOutput
     */
    public function testFilterSuccess(array $values, array $expectedResult): void
    {
        $output = $this->filter->filter($values);

        $this->assertCount(3, $output);
        $this->assertEquals($expectedResult[0], $output[TriBucketFilterEqualWidth::BUCKET_LOW]);
        $this->assertEquals($expectedResult[1], $output[TriBucketFilterEqualWidth::BUCKET_MEDIUM]);
        $this->assertEquals($expectedResult[2], $output[TriBucketFilterEqualWidth::BUCKET_HIGH]);
    }

    public function providerInputOutput(): array
    {
        return [
            [
                [0.1, 3.4, 3.5, 3.6, 7.0, 9.0, 6.0, 4.4, 2.5, 3.9, 4.5, 2.8],
                [
                    [0.1, 3.4, 3.5, 3.6, 2.5, 3.9, 2.8],
                    [7, 6, 4.4, 4.5],
                    [9],
                ],
            ],
        ];
    }
}
