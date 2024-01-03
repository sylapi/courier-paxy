<?php

namespace Sylapi\Courier\Paxy\Tests;

use Throwable;
use Sylapi\Courier\Contracts\Label;
use Sylapi\Courier\Paxy\CourierGetLabels;
use Sylapi\Courier\Paxy\Entities\LabelType;
use Sylapi\Courier\Paxy\PaxyCourierGetLabels;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\Tests\Helpers\SessionTrait;


class CourierGetLabelsTest extends PHPUnitTestCase
{
    use SessionTrait;

    public function testGetLabelSuccess()
    {
        $sessionMock = $this->getSessionMock([
            [
                'code'   => 200,
                'header' => [],
                'body'   => file_get_contents(__DIR__.'/Mock/PaxyCourierGetLabelSuccess.json'),
            ],
        ]);

        
        $courierGetLabels = new CourierGetLabels($sessionMock);
        $labelTypeMock = $this->createMock(LabelType::class);
        $response = $courierGetLabels->getLabel('123', $labelTypeMock);
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

        $courierGetLabels = new CourierGetLabels($sessionMock);
        $labelTypeMock = $this->createMock(LabelType::class);
        $this->expectException(TransportException::class);
        $courierGetLabels->getLabel('123', $labelTypeMock);
    } 
}
