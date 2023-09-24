<?php

declare(strict_types=1);

namespace App\Service;

/** @psalm-suppress UnusedClass */
class TriBucketFilterEqualWidth implements BucketFilterInterface
{
    use BucketFilterTrait;

    private readonly int|float $rangeLowStart;
    private readonly int|float $rangeLowEnd;
    private readonly int|float $rangeMediumStart;
    private readonly int|float $rangeMediumEnd;
    private readonly int|float $rangeHighStart;
    private readonly int|float $rangeHighEnd;

    public function __construct(int|float $bucketStart, int $bucketWidth)
    {
        $this->rangeLowStart = $bucketStart;
        $this->rangeLowEnd = $bucketStart + $bucketWidth;
        $this->rangeMediumStart = $this->rangeLowEnd;
        $this->rangeMediumEnd = $this->rangeMediumStart + $bucketWidth;
        $this->rangeHighStart = $this->rangeMediumEnd;
        $this->rangeHighEnd = $this->rangeHighStart + $bucketWidth;
    }

    public function filter(array $values): array
    {
        $values = $this->sanitizeValues($values);

        $output = [
            self::BUCKET_LOW => [],
            self::BUCKET_MEDIUM => [],
            self::BUCKET_HIGH => [],
        ];
        /** @var int|float $value */
        foreach ($values as $value) {
            if ($value >= $this->rangeLowStart && $value <= $this->rangeLowEnd) {
                $output[self::BUCKET_LOW][] = $value;
            } elseif ($value >= $this->rangeMediumStart && $value <= $this->rangeMediumEnd) {
                $output[self::BUCKET_MEDIUM][] = $value;
            } elseif ($value >= $this->rangeHighStart && $value <= $this->rangeHighEnd) {
                $output[self::BUCKET_HIGH][] = $value;
            }
        }

        return $output;
    }
}
