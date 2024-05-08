<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy\Entities;

use Sylapi\Courier\Abstracts\Options as OptionsAbstract;

class Options extends OptionsAbstract
{
    public function setShippingType(string $shippingType): self
    {
        $this->set('shippingType', $shippingType);
        return $this;
    }

    public function getShippingType(): ?string
    {
        return $this->get('shippingType');
    }

    public function validate(): bool
    {
        return true;
    }
}
