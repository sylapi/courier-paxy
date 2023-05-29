<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Courier;
use Sylapi\Courier\Paxy\PaxyBooking;
use Sylapi\Courier\Paxy\PaxyCourierApiFactory;
use Sylapi\Courier\Paxy\PaxyParameters;
use Sylapi\Courier\Paxy\PaxyParcel;
use Sylapi\Courier\Paxy\PaxyReceiver;
use Sylapi\Courier\Paxy\PaxySender;
use Sylapi\Courier\Paxy\PaxySession;
use Sylapi\Courier\Paxy\PaxySessionFactory;
use Sylapi\Courier\Paxy\PaxyShipment;

class PaxyCourierApiFactoryTest extends PHPUnitTestCase
{
    private $parameters = [
        'token'            => 'token',
        'organization_id'  => 'password',
        'sandbox'          => true,
        'labelType'        => 'normal',
    ];

    public function testPaxySessionFactory()
    {
        $PaxySessionFactory = new PaxySessionFactory();
        $PaxySession = $PaxySessionFactory->session(
            PaxyParameters::create($this->parameters)
        );
        $this->assertInstanceOf(PaxySession::class, $PaxySession);
    }

    public function testCourierFactoryCreate()
    {
        $PaxyCourierApiFactory = new PaxyCourierApiFactory(new PaxySessionFactory());
        $courier = $PaxyCourierApiFactory->create($this->parameters);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertInstanceOf(PaxyBooking::class, $courier->makeBooking());
        $this->assertInstanceOf(PaxyParcel::class, $courier->makeParcel());
        $this->assertInstanceOf(PaxyReceiver::class, $courier->makeReceiver());
        $this->assertInstanceOf(PaxySender::class, $courier->makeSender());
        $this->assertInstanceOf(PaxyShipment::class, $courier->makeShipment());
    }
}
