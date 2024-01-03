<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\PaxyParcel;

class ParcelTest extends PHPUnitTestCase
{
    public function testWidthConvertingCmToMm()
    {
        $value = rand(1, 2000);
        $parcel = new PaxyParcel();
        $parcel->setWidth($value);
        $this->assertEquals(($value * PaxyParcel::SIZE_IMPACT), $parcel->getWidth());
    }

    public function testHeighthConvertingCmToMm()
    {
        $value = rand(1, 2000);
        $parcel = new PaxyParcel();
        $parcel->setHeight($value);
        $this->assertEquals(($value * PaxyParcel::SIZE_IMPACT), $parcel->getHeight());
    }

    public function testLengthConvertingCmToMm()
    {
        $value = rand(1, 2000);
        $parcel = new PaxyParcel();
        $parcel->setLength($value);
        $this->assertEquals(($value * PaxyParcel::SIZE_IMPACT), $parcel->getLength());
    }
}
