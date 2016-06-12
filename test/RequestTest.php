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

        $this->request = new Request(
            'GET', 'http://localhost/test?id=9090', $server
        );

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

}