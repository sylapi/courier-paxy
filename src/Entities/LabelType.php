<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy\Entities;

use Sylapi\Courier\Abstracts\LabelType as LabelTypeAbstract;

class LabelType extends LabelTypeAbstract
{
    public function validate(): bool
    {
        return true;
    }
}
