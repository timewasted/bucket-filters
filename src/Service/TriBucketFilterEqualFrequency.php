<?php

declare(strict_types=1);

namespace App\Service;

/** @psalm-suppress UnusedClass */
class TriBucketFilterEqualFrequency implements BucketFilterInterface
{
    use BucketFilterTrait;

    public function filter(array $values): array
    {
        $values = $this->sanitizeValues($values);
        $valueCount = count($values);
        if (0 === $valueCount || 0 !== $valueCount % 3) {
            throw new \InvalidArgumentException('Can not perform equal frequency filtering due to invalid value count');
        }

        $bucketSize = (int) ($valueCount / 3);
        $sortedData = $values;
        asort($sortedData, SORT_NUMERIC);

        $output = [
            self::BUCKET_LOW => array_slice($sortedData, 0, $bucketSize, true),
            self::BUCKET_MEDIUM => array_slice($sortedData, $bucketSize, $bucketSize, true),
            self::BUCKET_HIGH => array_slice($sortedData, -$bucketSize, $bucketSize, true),
        ];
        foreach (array_keys($output) as $bucket) {
            ksort($output[$bucket]);
            $output[$bucket] = array_values($output[$bucket]);
        }

        return $output;
    }
}
