<?php

namespace Sylapi\Courier\Paxy\Tests;

use Throwable;
use Sylapi\Courier\Paxy\Responses\Parcel as ParcelResponse;
use Sylapi\Courier\Paxy\Entities\Booking;
use Sylapi\Courier\Paxy\CourierPostShipment;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\Tests\Helpers\SessionTrait;

class CourierPostShipmentTest extends PHPUnitTestCase
{
    use SessionTrait;

    private function getBookingMock(string $shipmentId, string $trackingId)
    {
        $bookingMock = $this->createMock(Booking::class);
        $bookingMock->method('getShipmentId')->willReturn($shipmentId);
        $bookingMock->method('getTrackingId')->willReturn($trackingId);
        $bookingMock->method('validate')->willReturn(true);

        return $bookingMock;
    }

    public function testPostShipmentSuccess()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 201,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierPostShipmentSuccess.json'),
            ],
        ]);

        $courierCreateShipment = new CourierPostShipment($sessionMock);
        $shipmentId = (string) 1234567890;
        $trackingId = (string) 987654321;
        $booking = $this->getBookingMock($shipmentId, $trackingId);
        $response = $courierCreateShipment->postShipment($booking);

        $this->assertInstanceOf(ParcelResponse::class, $response);
        $this->assertNotEmpty($response->getShipmentId());
        $this->assertEquals('1234567890', $response->getShipmentId());
        $this->assertNotEmpty($response->getTrackingId());
        $this->assertEquals($response->getTrackingId(), '987654321');
    }

    public function testPostShipmentFailure()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 401,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierPostShipmentFailure.json'),
            ],
        ]);

        $courierCreateShipment = new CourierPostShipment($sessionMock);
        $shipmentId = (string) 1234567890;
        $trackingId = (string) 987654321;
        $booking = $this->getBookingMock($shipmentId, $trackingId);
        $this->expectException(TransportException::class);
        $courierCreateShipment->postShipment($booking);
    }
}
