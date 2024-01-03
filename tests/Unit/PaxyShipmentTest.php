<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\PaxyParcel;
use Sylapi\Courier\Paxy\PaxyReceiver;
use Sylapi\Courier\Paxy\PaxySender;
use Sylapi\Courier\Paxy\PaxyShipment;

class ShipmentTest extends PHPUnitTestCase
{
    public function testNumberOfPackagesIsAlwaysEqualTo1()
    {
        $parcel = new PaxyParcel();
        $shipment = new PaxyShipment();
        $shipment->setParcel($parcel);
        $shipment->setParcel($parcel);

        $this->assertEquals(1, $shipment->getQuantity());
    }

    public function testShipmentValidate()
    {
        $receiver = new PaxyReceiver();
        $sender = new PaxySender();
        $parcel = new PaxyParcel();

        $shipment = new PaxyShipment();
        $shipment->setSender($sender)
            ->setReceiver($receiver)
            ->setParcel($parcel);

        $this->assertIsBool($shipment->validate());
        $this->assertIsBool($shipment->getReceiver()->validate());
        $this->assertIsBool($shipment->getSender()->validate());
        $this->assertIsBool($shipment->getParcel()->validate());
    }
}
