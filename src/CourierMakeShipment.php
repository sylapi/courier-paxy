<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Paxy\Entities\Shipment;
use Sylapi\Courier\Contracts\Shipment as ShipmentContract;
use Sylapi\Courier\Contracts\CourierMakeShipment as CourierMakeShipmentContract;

class CourierMakeShipment implements CourierMakeShipmentContract
{
    public function makeShipment(): ShipmentContract
    {
        return new Shipment();
    }
}