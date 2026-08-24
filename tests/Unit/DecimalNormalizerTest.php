<?php

namespace Tests\Unit;

use App\Support\DecimalNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DecimalNormalizerTest extends TestCase
{
    #[DataProvider('decimalValues')]
    public function test_it_normalizes_supported_decimal_formats(string $input, string $expected): void
    {
        $this->assertSame($expected, DecimalNormalizer::normalize($input));
    }

    public static function decimalValues(): array
    {
        return [
            'integer' => ['1000', '1000'],
            'brazilian decimal' => ['1000,50', '1000.50'],
            'brazilian thousands' => ['1.000,50', '1000.50'],
            'international thousands' => ['1,000.50', '1000.50'],
            'currency symbol' => ['R$ 1.000,50', '1000.50'],
            'negative' => ['-1', '-1'],
        ];
    }
}
