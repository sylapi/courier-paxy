<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use Sylapi\Courier\Paxy\Entities\Booking;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class BookingTest extends PHPUnitTestCase
{
    public function testValidatorBookingHasShipmentId()
    {
        $value = '1234567890';
        $booking = new Booking();
        $booking->setShipmentId($value);
        $this->assertTrue($booking->validate());
    }

    public function testValidatorBookingHasNotShipmentId()
    {
        $booking = new Booking();
        $this->assertFalse($booking->validate());
    }
}
