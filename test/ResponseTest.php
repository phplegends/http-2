<?php

use PHPLegends\Http\Response;
use PHPLegends\Http\ParameterCollection;
use PHPLegends\Http\ResponseHeaderCollection;

class ResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->response = new Response('Hello, world!', 200, [
            'X-ResponseTest' => 'Yep'
        ]);
    }

    public function test()
    {
        $this->assertEquals('Hello, world!', $this->response->getContent());

        $this->assertInstanceOf('PHPLegends\Http\ResponseHeaderCollection', $this->response->getHeaders());

    }

    public function testHeaderGet()
    {
        $this->assertEquals(
            ['Yep'],
            $this->response->getHeaders()->get('X-ResponseTest')
        );

        $this->assertEquals(
            'Yep',
            $this->response->getHeaders()->getLine('X-ResponseTest')
        );
    }


    public function testGetStatusCode()
    {
        $this->assertEquals(
            200,
            $this->response->getStatusCode()
        );
    }

   public function testSend()
   {
      $this->response->send(true);
   }


   public function testSetGetReasonPhrase()
   {
        $this->assertEquals('OK', $this->response->getReasonPhrase());

        $this->response->setReasonPhrase('Yep!');

        $this->assertEquals('Yep!', $this->response->getReasonPhrase());

        $this->response->setReasonPhrase(null);

        $this->assertEquals('OK', $this->response->getReasonPhrase());
   }


   public function testResolveHeaderValueOnConstructor()
   {
        $r1 = new Response('Array Header', 200, ['Hello' => 'World']);

        $this->assertEquals('World', $r1->getHeaders()->getLine('hello'));

        $r2 = new Response('Object Header', 200, $headers = new ResponseHeaderCollection([
            'Hello' => 'Object'
        ]));

        $this->assertEquals($headers, $r2->getHeaders());

        $this->assertEquals('Object', $r2->getHeaders()->getLine('hello'));

        $r3 = new Response('Null Header', 200, null);

        $this->assertEquals('', $r3->getHeaders()->getLine('hello'));
   }

   public function testGetCookies()
   {
        $this->assertInstanceOf('PHPLegends\Http\CookieJar', $this->response->getCookies());
   }

   public function testSetCookies()
   {
        $this->response->setCookies($cookies = new PHPLegends\Http\CookieJar());

        $cookies['name'] = 'Wallace';

        $this->assertEquals($cookies, $this->response->getCookies());
   }
}
