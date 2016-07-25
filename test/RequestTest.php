<?php

use PHPLegends\Http\Request;
use PHPLegends\Http\ParameterCollection;

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $server = new ParameterCollection([
            'HTTP_HOST' => 'localhost',
            'HTTP_PORT' => 80,
        ]);

        $this->request = new Request('GET', 'http://localhost/test?id=9090', [
            'Content-Length' => 0,
            'X-PHPUnit-Test' => 'Ok'
        ]);


        $this->request->setServer($server);

        $this->request->setBody(new ParameterCollection([
            'name' => 'Wallace',
            'age'  => 10
        ]));
    }

    public function testBody()
    {
        $r = $this->request;

        $this->assertParamCollection($r->getBody());

        $this->assertEquals(
            'Wallace',
            $r->getBody()->get('name')
        );

        $this->assertEquals(
            10,
            $r->getBody()->get('age')
        );

        $this->assertCount(2, $r->getBody());
    }

    public function testGetUri()
    {
        $r = $this->request;

        $this->assertInstanceOf('PHPLegends\Http\Uri', $r->getUri());

        $this->assertEquals('/test', $r->getUri()->getPath());
    }

    public function testGetServer()
    {
        $r = $this->request;

        $this->assertParamCollection(
            $r->getServer()
        );

        $this->assertCount(2, $r->getServer());

        $this->assertEquals(
            'localhost', $r->getServer()->get('HTTP_HOST')
        );
    }

    public function testGetQuery()
    {
        $r = $this->request;

        $this->assertParamCollection($r->getQuery());

        $this->assertCount(1, $r->getQuery());

        $this->assertEquals('9090', $r->getQuery()->get('id'));
    }

    public function testGetRawBody()
    {
        $this->assertNull($this->request->getContent());

        $this->request->setContent('{"id":1}');

        $this->assertEquals('{"id":1}', $this->request->getContent());

        $data = $this->request->getJsonContent();

        $this->assertEquals(['id' => 1], $data);
    }

    public function testGetContentException()
    {
        $this->request->setContent('{invalid_json...');

        try{

            $this->request->getJsonContent();

        } catch (\Exception $e) {

            $this->assertInstanceOf('\RunTimeException', $e);
        }
    }

    public function testCreateFromGlobalsAndUploaded()
    {
        $_FILES = [
            'name' => [
                'tmp_name' => tempnam(null, '_test_phpunit_'),
                'error'    => UPLOAD_ERR_OK, 
                'name'     => 'file.txt', 
                'size'     => 99, 
                'type'     => 'text/plain'
            ],
            'group' => [
                'subname' => [
                   'tmp_name' => tempnam(null, '_test_phpunit_'),
                    'error'    => UPLOAD_ERR_OK, 
                    'name'     => 'test_multilevel_array.txt', 
                    'size'     => 99, 
                    'type'     => 'text/plain'
                ]
            ],

            'parte_chata' => [
                'tmp_name' => [
                    tempnam(null, '_test_phpunit_'),
                    tempnam(null, '_test_phpunit_')
                ],
                'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_OK],
                'name'  => ['x.txt', 'y.txt'],
                'size'  => [40, 50],
                'type'  => ['text/plain', 'application/pdf']
            ]
        ];

        $_SERVER['HTTP_Accept_Language'] = "pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4,ru;q=0.2";

        $_SERVER['HTTP_Accept-Encoding'] = "gzip, deflate, sdch";

        $r = Request::createFromGlobals();

        $this->assertEquals(
            'file.txt',
            $r->getUploadedFiles()->get('name')->getClientFilename()
        );

        $this->assertEquals(
            'file.txt',
            $r->getUploadedFiles()->get('name')->getClientFilename()
        );

        $this->assertEquals(
            'test_multilevel_array.txt',
            $r->getUploadedFiles()->get('group')['subname']->getClientFilename()
        );

    }

    protected function assertParamCollection($value)
    {
        return $this->assertInstanceOf(
            'PHPLegends\Http\ParameterCollection', $value
        );
    }


    public function testGetHeaders()
    {
        $this->assertInstanceOf('PHPLegends\Http\HeaderCollection', $this->request->getHeaders());

        $this->assertCount(2, $this->request->getHeaders());

        $this->assertTrue($this->request->getHeaders()->has('content-length'));

        $this->assertEquals('Ok', $this->request->getHeaders()->getLine('x-phpunit-test'));
    }

    public function test__Get()
    {
        $this->request->getQuery()->set('id', 1);

        $this->assertEquals(
            $this->request->getQuery()->get('id'),
            $this->request->query['id']
        );

        $this->assertEquals(
            $this->request->getBody()->get('age'),
            $this->request->body['age']
        );

        $this->assertEquals(
            $this->request->getServer()->get('HTTP_HOST'),
            $this->request->server['HTTP_HOST']
        );

        $this->request->cookies['nome'] = 'Wallace';

        $this->assertEquals(
            $this->request->getCookies()->get('nome'),
            $this->request->cookies['nome']
        );

        $this->assertEquals([0], $this->request->headers['Content-Length']);

        try {

            $this->request->sorry;

        } catch (\UnexpectedValueException $e) {

            $this->assertEquals("The property 'sorry' doesnt exists", $e->getMessage());
        }
    }

    public function testIsXhr()
    {

        $this->assertFalse($this->request->isXhr());

        $this->request->getHeaders()->set('x-requested-with', 'xmlhttprequest');

        $this->assertTrue($this->request->isXhr());
    }

}