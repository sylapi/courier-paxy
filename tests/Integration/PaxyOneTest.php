<?php

namespace Sylapi\Courier\Inpost\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Inpost\InpostCourierCreateShipment;
use Sylapi\Courier\Inpost\InpostParcel;
use Sylapi\Courier\Inpost\InpostReceiver;
use Sylapi\Courier\Inpost\InpostSender;
use Sylapi\Courier\Inpost\InpostShipment;
use Sylapi\Courier\Inpost\Tests\Helpers\InpostSessionTrait;
use Throwable;

class PaxyOneTest extends PHPUnitTestCase
{
    public function testOne()
    {
        $var = true;
        $this->assertTrue($var);
    }
}
