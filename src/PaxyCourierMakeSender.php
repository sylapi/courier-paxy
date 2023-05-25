<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Contracts\CourierMakeSender;
use Sylapi\Courier\Contracts\Sender;

class PaxyCourierMakeSender implements CourierMakeSender
{
    public function makeSender(): Sender
    {
        return new PaxySender();
    }
}
