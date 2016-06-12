<?php

use PHPLegends\Http\RedirectResponse;

class RedirectResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->redirect = new RedirectResponse('/bye');
    }

    public function testLocation()
    {
        $location = $this->redirect->getLocation();

        $this->assertEquals('/bye', $location);
    }

    public function testStatusCode()
    {
        $this->assertEquals(302, $this->redirect->getStatusCode());
    }

    public function testHeaderLocationIsPresent()
    {
        $this->assertTrue($this->redirect->getHeaders()->has('Location'));
    }

    public function testTestExceptionInSetContent()
    {
        try {
            
            $this->redirect->setContent('Isso vai dar pau, hahhaha');
            
        } catch (\Exception $e) {
            
            $this->assertInstanceOf('\BadMethodCallException', $e);
        }
    }
}