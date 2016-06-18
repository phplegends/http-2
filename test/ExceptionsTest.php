<?php

use PHPLegends\Http\Exceptions\HttpException;
use PHPLegends\Http\Exceptions\NotFoundException;

class ExceptionsTest extends PHPUnit_Framework_TestCase
{

    public function testHttpExceptionDefault()
    {
        $e = new HttpException('Internal server error');

        $this->assertEquals(500, $e->getStatusCode());

        $this->assertEquals('Internal server error', $e->getMessage());

        $e = new HttpException('Method not allowed', 405);

        $this->assertEquals(405, $e->getStatusCode());

        $this->assertEquals('Method not allowed', $e->getMessage());

    }


    public function testNotFoundException()
    {
        $e = new NotFoundException('Not found');

        $this->assertEquals('Not found', $e->getMessage());

        $this->assertEquals(404, $e->getStatusCode());
    }
}