<?php

namespace Sylapi\Courier\Paxy\Tests;

use Throwable;
use Sylapi\Courier\Contracts\Status;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Paxy\CourierGetStatuses;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\Tests\Helpers\SessionTrait;



class CourierGetStatusTest extends PHPUnitTestCase
{
    use SessionTrait;

    public function testGetStatusSuccess()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 200,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierGetStatusSuccess.json'),
            ],
        ]);

        $courierGetStatuses = new CourierGetStatuses($sessionMock);

        $response = $courierGetStatuses->getStatus('123');
        $this->assertEquals($response, StatusType::NEW->value);
    }

    public function testGetStatusFailure()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 401,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierGetStatusFailure.json'),
            ],
        ]);

        $this->expectException(TransportException::class);
        $courierGetStatuses = new CourierGetStatuses($sessionMock);
        $courierGetStatuses->getStatus('123');
    }
}
