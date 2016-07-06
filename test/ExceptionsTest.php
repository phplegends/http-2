<?php

use PHPLegends\Http\Exceptions\HttpException;
use PHPLegends\Http\Exceptions\NotFoundException;
use PHPLegends\Http\Exceptions\MethodNotAllowedException;
use PHPLegends\Http\Request;

class ExceptionsTest extends PHPUnit_Framework_TestCase
{

    public function testHttpExceptionDefault()
    {
        $e = new HttpException('Internal server error');

        $this->assertEquals(500, $e->getResponse()->getStatusCode());

        $this->assertEquals('Internal server error', $e->getMessage());

        $e = new HttpException('Method not allowed', 405);

        $this->assertEquals(405, $e->getResponse()->getStatusCode());

        $this->assertEquals('Method not allowed', $e->getMessage());

    }


    public function testNotFoundException()
    {
        $e = new NotFoundException('Not found');

        $this->assertEquals('Not found', $e->getMessage());

        $this->assertEquals(404, $e->getResponse()->getStatusCode());
    }

    public function testNotAllowedException()
    {
        $request = new Request('GET', '/post');

        $e = MethodNotAllowedException::createFromRequest($request);

        $this->assertEquals(405, $e->getResponse()->getStatusCode());
        
        $this->assertEquals('Method "GET" not allowed in "/post" path', $e->getMessage());




        $e = new MethodNotAllowedException('Não vale esse método');

        $this->assertEquals(405, $e->getResponse()->getStatusCode());

        $this->assertEquals('Não vale esse método', $e->getMessage());

    }
}