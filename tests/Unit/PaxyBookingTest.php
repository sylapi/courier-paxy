<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\PaxyBooking;

class PaxyBookingTest extends PHPUnitTestCase
{
    public function testValidatorBookingHasShipmentId()
    {
        $value = '1234567890';
        $booking = new PaxyBooking();
        $booking->setShipmentId($value);
        $this->assertTrue($booking->validate());
    }

    public function testValidatorBookingHasNotShipmentId()
    {
        $booking = new PaxyBooking();
        $this->assertFalse($booking->validate());
    }
}
