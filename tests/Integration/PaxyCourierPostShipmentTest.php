<?php

namespace Sylapi\Courier\Paxy\Tests;

use Throwable;
use Sylapi\Courier\Paxy\PaxyBooking;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Paxy\PaxyCourierPostShipment;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\Tests\Helpers\PaxySessionTrait;


class CourierPostShipmentTest extends PHPUnitTestCase
{
    use PaxySessionTrait;


    private function getBookingMock(string $shipmentId, string $trackingId)
    {
        $bookingMock = $this->createMock(PaxyBooking::class);
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

        $PaxyCourierCreateShipment = new PaxyCourierPostShipment($sessionMock);
        $shipmentId = (string) 1234567890;
        $trackingId = (string) 987654321;
        $booking = $this->getBookingMock($shipmentId, $trackingId);
        $response = $PaxyCourierCreateShipment->postShipment($booking);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->shipmentId);
        $this->assertEquals('1234567890', $response->shipmentId);
        $this->assertNotEmpty($response->trackingId);
        $this->assertEquals($response->trackingId, '987654321');
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

        $PaxyCourierCreateShipment = new PaxyCourierPostShipment($sessionMock);
        $shipmentId = (string) 1234567890;
        $trackingId = (string) 987654321;
        $booking = $this->getBookingMock($shipmentId, $trackingId);
        $response = $PaxyCourierCreateShipment->postShipment($booking);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Throwable::class, $response->getFirstError());
        $this->assertInstanceOf(TransportException::class, $response->getFirstError());
    }
}
