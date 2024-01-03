<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Exceptions\ValidateException;
use Sylapi\Courier\Paxy\PaxyParameters;

class ParametersTest extends PHPUnitTestCase
{
    public function testHasProperty()
    {
        $parameters = PaxyParameters::create([
            'test' => 1,
        ]);

        $this->assertTrue($parameters->hasProperty('test'));
    }

    public function testNotHasProperty()
    {
        $parameters = PaxyParameters::create([
            'test1' => 2,
        ]);

        $this->assertFalse($parameters->hasProperty('test'));
    }

    public function testNotHasPropertyDispatchPoint()
    {
        $this->expectException(ValidateException::class);
        $parameters = PaxyParameters::create([
            'test1' => 2,
        ]);
        $parameters->getDispatchPoint();
    }

    public function testHasPropertyDispatchPointId()
    {
        $value = 123456;
        $parameters = PaxyParameters::create([
            'dispatch_point_id' => $value,
        ]);

        $this->assertEquals(['dispatch_point_id' => $value], $parameters->getDispatchPoint());
    }
}
