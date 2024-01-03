<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Paxy\Entities\Service;
use Sylapi\Courier\Contracts\Service as ServiceContract;
use Sylapi\Courier\Contracts\CourierMakeService as CourierMakeServiceContract;
use Sylapi\Courier\ServiceFactory;

class CourierMakeService implements CourierMakeServiceContract
{
    public function makeService(?string $serviceType = null): ServiceContract
    {
        if($serviceType) {
            return ServiceFactory::create('Paxy', $serviceType);
        }
        
        return new Service();
    }
}
