<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use Sylapi\Courier\Courier;
use Sylapi\Courier\Paxy\Session;
use Sylapi\Courier\Paxy\SessionFactory;
use Sylapi\Courier\Paxy\Entities\Parcel;
use Sylapi\Courier\Paxy\Entities\Sender;
use Sylapi\Courier\Paxy\Entities\Booking;
use Sylapi\Courier\Paxy\CourierApiFactory;
use Sylapi\Courier\Paxy\Entities\Receiver;
use Sylapi\Courier\Paxy\Entities\Shipment;
use Sylapi\Courier\Paxy\Entities\Credentials;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class CourierApiFactoryTest extends PHPUnitTestCase
{
    public function testPaxySessionFactory()
    {
        $credentials = new Credentials();
        $credentials->setLogin('login');
        $credentials->setPassword('password');
        $credentials->setSandbox(true);

        $sessionFactory = new SessionFactory();
        $session = $sessionFactory->session(
            $credentials
        );
        $this->assertInstanceOf(Session::class, $session);
    }

    public function testCourierFactoryCreate()
    {

        $credentials = [
            'login' => 'login',
            'password' => 'password',
            'sandbox' => true,
        ];

        $courierApiFactory = new CourierApiFactory(new SessionFactory());
        $courier = $courierApiFactory->create($credentials);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertInstanceOf(Booking::class, $courier->makeBooking());
        $this->assertInstanceOf(Parcel::class, $courier->makeParcel());
        $this->assertInstanceOf(Receiver::class, $courier->makeReceiver());
        $this->assertInstanceOf(Sender::class, $courier->makeSender());
        $this->assertInstanceOf(Shipment::class, $courier->makeShipment());
    }
}
