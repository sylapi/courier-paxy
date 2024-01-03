<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use ArrayObject;
use Sylapi\Courier\Exceptions\ValidateException;

class Parameters extends ArrayObject
{


    public static function create(array $parameters): self
    {
        return new self($parameters, ArrayObject::ARRAY_AS_PROPS);
    }

    public function getDispatchPoint()
    {
        if ($this->hasProperty('dispatch_point_id')) {
            return [
                'dispatch_point_id' => $this->dispatch_point_id,
            ];
        } elseif ($this->hasProperty('dispatch_point')) {
            return [
                'address' => $this->dispatch_point,
            ];
        } else {
            throw new ValidateException('Dispatch point is not defined');
        }
    }

    public function hasProperty(string $name)
    {
        return property_exists($this, $name);
    }
}
