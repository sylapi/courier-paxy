<?php

namespace Sylapi\Courier\Paxy\Tests;

use Throwable;
use Sylapi\Courier\Contracts\Label;
use Sylapi\Courier\Paxy\PaxyCourierGetLabels;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\Tests\Helpers\PaxySessionTrait;


class PaxyCourierGetLabelsTest extends PHPUnitTestCase
{
    use PaxySessionTrait;

    public function testGetLabelSuccess()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 200,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierGetLabelSuccess.json'),
            ],
        ]);

        $PaxyCourierGetLabels = new PaxyCourierGetLabels($sessionMock);

        $response = $PaxyCourierGetLabels->getLabel('123');
        $this->assertEquals($response, 'JVBERi0xLjcKOCAwIG9iago8PCAv');
    }

    public function testGetLabelFailure()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 401,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierGetLabelFailure.json'),
            ],
        ]);

        $PaxyCourierGetLabels = new PaxyCourierGetLabels($sessionMock);
        $response = $PaxyCourierGetLabels->getLabel('123');
        $this->assertInstanceOf(Label::class, $response);
        $this->assertEquals(null, (string) $response);
        $this->assertInstanceOf(Throwable::class, $response->getFirstError());
        $this->assertInstanceOf(TransportException::class, $response->getFirstError());
        $this->assertEquals('Authentication failed', $response->getFirstError()->getMessage());
    } 
}
