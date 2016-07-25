<?php

use PHPLegends\Http\FilesCollection;
use PHPLegends\Http\UploadedFile;

class FilesCollectionTest extends PHPUnit_Framework_TestCase
{

    protected function getPHPFilesMock()
    {
        static $a = 0;

        $a++;

        return [
            'tmp_name' => sprintf('/tmp/temp_%d', $a),
            'name'     => sprintf('text_%d.txt', $a),
            'size'     => 150,
            'error'    => UPLOAD_ERR_OK,
            'type'     => 'text/plain',
        ];
    }

    public function setUp()
    {
        $this->files = FilesCollection::createFromArray([
            'users' => [
                'files' => [
                    $this->getPHPFilesMock(),
                    $this->getPHPFilesMock(),
                    $this->getPHPFilesMock(),
                    $this->getPHPFilesMock(),
                    $this->getPHPFilesMock(),
                ]
            ]
        ]);
    }   

    public function testSet()
    {
        $uploaded = new UploadedFile('/tmp/temp', 'file.txt', 80, UPLOAD_ERR_OK, 'text/plain');

        $this->assertFalse($this->files->has('text'));

        $this->files->set('text', $uploaded);

        $this->assertTrue($this->files->has('text'));

    }

    public function testGet()
    {
        $this->assertInstanceOf('PHPLegends\Http\FilesCollection', $this->files->get('users'));

        $this->assertInstanceOf('PHPLegends\Http\FilesCollection', $this->files['users']['files']);

        $this->assertInstanceOf('PHPLegends\Http\UploadedFile', $this->files['users']['files']->first());
    }

    public function testHas()
    {
        $this->assertTrue($this->files->has('users'));

        $this->assertTrue(isset($this->files['users']['files']));

        $this->assertTrue($this->files['users']->has('files'));

        $this->assertFalse(isset($this->files['users']['fails']));
    }

    public function testCountable()
    {

        $this->assertCount(5, $this->files['users']['files']);

        $file = $this->files['users']['files']->pop();

        $this->assertCount(4, $this->files['users']['files']);
    }
}