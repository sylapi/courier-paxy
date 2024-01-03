<?php

namespace Sylapi\Courier\Inpost\Tests;

use Throwable;
use Sylapi\Courier\Paxy\Entities\Parcel;
use Sylapi\Courier\Paxy\Entities\Sender;
use Sylapi\Courier\Paxy\Entities\Receiver;
use Sylapi\Courier\Paxy\Entities\Shipment;
use Sylapi\Courier\Paxy\CourierCreateShipment;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\Responses\Shipment as ResponsesShipment;
use Sylapi\Courier\Paxy\Tests\Helpers\SessionTrait;


class CourierCreateShipmentTest extends PHPUnitTestCase
{
    use SessionTrait;

    private function getShipmentMock()
    {
        $senderMock = $this->createMock(Sender::class);
        $receiverMock = $this->createMock(Receiver::class);
        $parcelMock = $this->createMock(Parcel::class);
        $shipmentMock = $this->createMock(Shipment::class);

        $shipmentMock->method('getSender')
                ->willReturn($senderMock);

        $shipmentMock->method('getReceiver')
                ->willReturn($receiverMock);

        $shipmentMock->method('getParcel')
                ->willReturn($parcelMock);

        return $shipmentMock;
    }    

    public function testCreateShipmentSuccess()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 200,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierCreateShipmentBookSuccess.json'),
            ],
            [
                'code'   => 200,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierCreateShipmentParcelSuccess.json'),
            ],            
        ]);
        $courierCreateShipment = new CourierCreateShipment($sessionMock);
        $response = $courierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertInstanceOf(ResponsesShipment::class, $response);
        $this->assertNotEmpty($response->getReferenceId());
        $this->assertEquals('832/201912091206083212967', $response->getReferenceId());
        $this->assertNotEmpty($response->getShipmentId());
        $this->assertEquals('832/201912091206083212967', $response->getShipmentId());
        $this->assertNotEmpty($response->getTrackingId());
        $this->assertEquals('CP123123123', $response->getTrackingId());        
    }

    public function testCreateShipmentBookFailure()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 400,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierCreateShipmentBookFailure.json'),
            ],
            [
                'code'   => 200,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierCreateShipmentParcelSuccess.json'),
            ],            
        ]);
        $courierCreateShipment = new CourierCreateShipment($sessionMock);
        $courierCreateShipment->createShipment($this->getShipmentMock());
        
        $this->expectException(TransportException::class);
    }


    public function testCreateShipmentParcelFailure()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 200,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierCreateShipmentBookSuccess.json'),
            ],
            [
                'code'   => 401,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierCreateShipmentParcelFailure.json'),
            ],            
        ]);

        $this->expectException(TransportException::class);
        $courierCreateShipment = new CourierCreateShipment($sessionMock);
        $courierCreateShipment->createShipment($this->getShipmentMock());
        
    }    
}
