<?php

use PHPLegends\Http\CookieJar;
use PHPLegends\Http\Cookie;

class CookieTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cookies = new CookieJar;
    }

    public function testSet()
    {
        $this->cookies->set('name', 'value');

        $cookie = $this->cookies->get('name');

        // Testing default values

        $this->assertEquals(
            'value',
           $cookie->getValue()
        );
        
        $this->assertEquals(0, $cookie->getExpires());

        $this->assertNull($cookie->getPath());

        $this->assertFalse($cookie->getHttpOnly());

        $this->assertFalse($cookie->getSecure());

        $this->assertEquals('name', $cookie->getName());

        $this->assertNull($cookie->getDomain());
    }

    public function testSetCookie()
    {
        $expire = strtotime('+1 days');

        $this->cookies->setCookie(
            'session_id', 'BlaBlaBla', ['expires' => '+1 days', 'path' => '/test']
        );

        $cookie = $this->cookies['session_id'];

        $this->assertEquals('session_id', $cookie->getName());

        $this->assertEquals('BlaBlaBla', $cookie->getValue());

        $this->assertEquals($expire, $cookie->getExpires());

        $this->assertEquals('/test', $cookie->getPath());

    }

    public function testAdd()
    {
        $datetime = new DateTime('+2 days');

        $cookie = Cookie::create('test_add', 'value', [
            'httpOnly' => true,
            'expires'  => $datetime
        ]);

        $this->cookies->add($cookie);

        $this->assertEquals($cookie, $this->cookies->get('test_add'));

        $this->assertTrue($cookie->getHttpOnly());

        $this->assertEquals(
            $datetime->format('U'), $cookie->getExpires()
        );

        $this->assertEquals('value', $cookie->getValue());

        $this->assertEquals('test_add', $cookie->getName());
    }


}