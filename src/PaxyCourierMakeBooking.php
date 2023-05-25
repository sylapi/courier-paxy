<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierMakeBooking;

class PaxyCourierMakeBooking implements CourierMakeBooking
{
    public function makeBooking(): Booking
    {
        return new PaxyBooking();
    }
}
