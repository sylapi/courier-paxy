<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Contracts\CourierMakeParcel;
use Sylapi\Courier\Contracts\Parcel;

class PaxyCourierMakeParcel implements CourierMakeParcel
{
    public function makeParcel(): Parcel
    {
        return new PaxyParcel();
    }
}
