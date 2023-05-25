<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Abstracts\Receiver;

class PaxyReceiver extends Receiver
{
    public function validate(): bool
    {
        return true;
    }
}
