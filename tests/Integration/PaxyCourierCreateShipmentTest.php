<?php

namespace Sylapi\Courier\Inpost\Tests;

use Throwable;
use Sylapi\Courier\Paxy\PaxyParcel;
use Sylapi\Courier\Paxy\PaxySender;
use Sylapi\Courier\Paxy\PaxyReceiver;
use Sylapi\Courier\Paxy\PaxyShipment;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\PaxyCourierCreateShipment;
use Sylapi\Courier\Paxy\Tests\Helpers\PaxySessionTrait;


class PaxyCourierCreateShipmentTest extends PHPUnitTestCase
{

    use PaxySessionTrait;

    private function getShipmentMock()
    {
        $senderMock = $this->createMock(PaxySender::class);
        $receiverMock = $this->createMock(PaxyReceiver::class);
        $parcelMock = $this->createMock(PaxyParcel::class);
        $shipmentMock = $this->createMock(PaxyShipment::class);

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
        $paxyCourierCreateShipment = new PaxyCourierCreateShipment($sessionMock);
        $response = $paxyCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->referenceId);
        $this->assertEquals('832/201912091206083212967', $response->referenceId);
        $this->assertNotEmpty($response->shipmentId);
        $this->assertEquals('832/201912091206083212967', $response->shipmentId);
        $this->assertNotEmpty($response->trackingId);
        $this->assertEquals('CP123123123', $response->trackingId);        
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
        $paxyCourierCreateShipment = new PaxyCourierCreateShipment($sessionMock);
        $response = $paxyCourierCreateShipment->createShipment($this->getShipmentMock());
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Throwable::class, $response->getFirstError());
        $this->assertInstanceOf(TransportException::class, $response->getFirstError());
        $this->assertEquals('A new book for countryCode CZ cannot be created because such book is open in the system. Number of open book: 898201920230525090050202', $response->getFirstError()->getMessage());
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
        $paxyCourierCreateShipment = new PaxyCourierCreateShipment($sessionMock);
        $response = $paxyCourierCreateShipment->createShipment($this->getShipmentMock());
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Throwable::class, $response->getFirstError());
        $this->assertInstanceOf(TransportException::class, $response->getFirstError());
        $this->assertEquals('Authentication failed', $response->getFirstError()->getMessage());
    }    
}
