<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Paxy\PaxyCourier as Courier;

class PaxyCourierApiFactory
{
    private $PaxySessionFactory;

    public function __construct(PaxySessionFactory $PaxySessionFactory)
    {
        $this->PaxySessionFactory = $PaxySessionFactory;
    }

    public function create(array $parameters): Courier
    {
        $session = $this->PaxySessionFactory
                    ->session(PaxyParameters::create($parameters));

        return new Courier(
            new PaxyCourierCreateShipment($session),
            new PaxyCourierPostShipment($session),
            new PaxyCourierGetLabels($session),
            new PaxyCourierGetStatuses($session),
            new PaxyCourierMakeShipment(),
            new PaxyCourierMakeParcel(),
            new PaxyCourierMakeReceiver(),
            new PaxyCourierMakeSender(),
            new PaxyCourierMakeBooking()
        );
    }
}
