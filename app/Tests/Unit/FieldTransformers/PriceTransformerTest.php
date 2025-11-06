<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldTransformers;

use App\Services\Transformer\FieldTransformers\PriceTransformer;
use Mockery;
use Tests\UnitTestBase;

class PriceTransformerTest extends UnitTestBase
{
    private PriceTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transformer = resolve(PriceTransformer::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_transforms_positive_price_correctly()
    {
        $price           = 150000.0;
        $expectedHuman   = '150 هزار تومان';
        $expectedSummary = '150 ه.ت';

        $result = $this->transformer->transform($price);

        $this->assertEquals($price, $result['main']);
        $this->assertEquals($expectedHuman, $result['human']);
        $this->assertEquals($expectedSummary, $result['human_summary']);
        $this->assertEquals('150,000', $result['readable']);
        $this->assertEquals('تومان', $result['currency']);
        $this->assertEquals('150 هزار تومان', $result['readable_label']);
    }

    public function test_transforms_zero_price_as_free()
    {
        $price                 = 0.0;
        $expectedHuman         = 'رایگان';
        $expectedSummary       = 'رایگان';
        $expectedReadable      = '0';
        $expectedReadableLabel = 'رایگان';

        $result = $this->transformer->transform($price);

        $this->assertEquals($price, $result['main']);
        $this->assertEquals($expectedHuman, $result['human']);
        $this->assertEquals($expectedSummary, $result['human_summary']);
        $this->assertEquals($expectedReadable, $result['readable']);
        $this->assertEquals('تومان', $result['currency']);
        $this->assertEquals($expectedReadableLabel, $result['readable_label']);
    }

    public function test_transforms_negative_price_correctly()
    {
        $price                 = -50000.0;
        $expectedHuman         = 'رایگان';
        $expectedSummary       = 'رایگان';
        $expectedReadable      = '-50,000';
        $expectedReadableLabel = 'رایگان';

        $result = $this->transformer->transform($price);

        $this->assertEquals($price, $result['main']);
        $this->assertEquals($expectedHuman, $result['human']);
        $this->assertEquals($expectedSummary, $result['human_summary']);
        $this->assertEquals($expectedReadable, $result['readable']);
        $this->assertEquals('تومان', $result['currency']);
        $this->assertEquals($expectedReadableLabel, $result['readable_label']);
    }

    public function test_transforms_price_with_decimals()
    {
        $price            = 123456.789;
        $expectedHuman    = '123.5 هزار تومان';
        $expectedSummary  = '123.5 ه.ت';
        $expectedReadable = '123,457';

        $result = $this->transformer->transform($price);

        $this->assertEquals($price, $result['main']);
        $this->assertEquals($expectedHuman, $result['human']);
        $this->assertEquals($expectedSummary, $result['human_summary']);
        $this->assertEquals($expectedReadable, $result['readable']);
        $this->assertEquals('تومان', $result['currency']);
        $this->assertEquals('123.5 هزار تومان', $result['readable_label']);
    }
}
