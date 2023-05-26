<?php

namespace Sylapi\Courier\Paxy\Tests;

use Throwable;
use Sylapi\Courier\Contracts\Status;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Paxy\PaxyCourierGetStatuses;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\Tests\Helpers\PaxySessionTrait;


class PaxyCourierGetStatusTest extends PHPUnitTestCase
{
    use PaxySessionTrait;

    public function testGetStatusSuccess()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 200,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierGetStatusSuccess.json'),
            ],
        ]);

        $paxyCourierGetStatuses = new PaxyCourierGetStatuses($sessionMock);

        $response = $paxyCourierGetStatuses->getStatus('123');
        $this->assertInstanceOf(Status::class, $response);
        $this->assertEquals((string) $response, StatusType::ENTRY_WAIT);
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

        $PaxyCourierGetStatuses = new PaxyCourierGetStatuses($sessionMock);
        $response = $PaxyCourierGetStatuses->getStatus('123');
        $this->assertInstanceOf(Status::class, $response);
        $this->assertEquals(StatusType::APP_RESPONSE_ERROR, (string) $response);
        $this->assertInstanceOf(Throwable::class, $response->getFirstError());
        $this->assertInstanceOf(TransportException::class, $response->getFirstError());
    }
}
