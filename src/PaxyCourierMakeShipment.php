<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Contracts\CourierMakeShipment;
use Sylapi\Courier\Contracts\Shipment;

class PaxyCourierMakeShipment implements CourierMakeShipment
{
    public function makeShipment(): Shipment
    {
        return new PaxyShipment();
    }
}
