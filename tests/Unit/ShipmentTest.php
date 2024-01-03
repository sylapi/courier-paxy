<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use Sylapi\Courier\Paxy\Entities\Parcel;
use Sylapi\Courier\Paxy\Entities\Sender;
use Sylapi\Courier\Paxy\Entities\Receiver;
use Sylapi\Courier\Paxy\Entities\Shipment;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class ShipmentTest extends PHPUnitTestCase
{
    public function testNumberOfPackagesIsAlwaysEqualTo1()
    {
        $parcel = new Parcel();
        $shipment = new Shipment();
        $shipment->setParcel($parcel);
        $shipment->setParcel($parcel);

        $this->assertEquals(1, $shipment->getQuantity());
    }

    public function testShipmentValidate()
    {
        $receiver = new Receiver();
        $sender = new Sender();
        $parcel = new Parcel();

        $shipment = new Shipment();
        $shipment->setSender($sender)
            ->setReceiver($receiver)
            ->setParcel($parcel);

        $this->assertIsBool($shipment->validate());
        $this->assertIsBool($shipment->getReceiver()->validate());
        $this->assertIsBool($shipment->getSender()->validate());
        $this->assertIsBool($shipment->getParcel()->validate());
    }
}
