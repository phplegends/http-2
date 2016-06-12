<?php

use PHPLegends\Http\UploadedFile;

class UploadedFileTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->temp = tempnam(null, '__test__');

        $this->upload = new UploadedFile($this->temp, 'virtual.txt', 400, UPLOAD_ERR_NO_TMP_DIR, 'text/plain');
    }

    public function testGetFilename()
    {
        $this->assertEquals($this->temp, $this->upload->getFilename());
    }

    public function testGetClienteFilename()
    {
        $this->assertEquals('virtual.txt', $this->upload->getClientFilename());
    }

    public function testGetSize()
    {
        $this->assertEquals(400, $this->upload->getSize());
    }    

    public function testIsValid()
    {
        $this->assertFalse($this->upload->isValid());
    }

    public function testIsMoved()
    {
        $this->assertFalse($this->upload->isMoved());
    }

    public function testGetError()
    {
        $this->assertEquals(UPLOAD_ERR_NO_TMP_DIR, $this->upload->getError());
    }

    public function testGetErrorMessage()
    {
        $m = 'Missing a temporary folder';

        $this->assertEquals($m, $this->upload->getErrorMessage());
    }

    public function testGetClientMediaType()
    {
        $this->assertEquals('text/plain', $this->upload->getClientMediaType());
    } 
    
}