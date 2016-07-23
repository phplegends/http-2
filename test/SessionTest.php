<?php

use PHPLegends\Http\Session;
use PHPLegends\Session\Handlers\FileHandler;

class SessionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->session = new Session(new FileHandler(), null, 'PHPUNIT_SESS');

        $this->session->setId('__id__');
    }

    public function testSetFileTime()
    {
        $this->session->setLifetime('+1 day');

        $this->assertEquals(86400, $this->session->getLifetime());

        $this->session->setLifetime(30);

        $this->assertEquals(30, $this->session->getLifetime());

        $this->session->setLifetime(new DateTime('+1 minute'));

        $this->assertEquals(60, $this->session->getLifetime());
    }

    public function testGetId()
    {
        $this->assertEquals('__id__', $this->session->getId());
    }

    public function testGetName()
    {
        $this->assertEquals('PHPUNIT_SESS', $this->session->getName());
    }

    public function testSet()
    {
        $this->session->set('name', 'wallace');

        $this->assertEquals('wallace', $this->session->get('name'));
    }

    public function testCookie()
    {
        $this->session->setLifetime('+1 days');

        $cookie = $this->session->getCookie();  

        $this->assertEquals('PHPUNIT_SESS', $cookie->getName());

        $this->assertEquals('__id__', $cookie->getValue());
    }
}