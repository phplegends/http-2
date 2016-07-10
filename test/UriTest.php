<?php

use PHPLegends\Http\Uri;

class UriTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->uri = new Uri('http://localhost/path?name=Wallace');
    }

    public function testPort()
    {
        $this->assertEquals(80, $this->uri->getPort());
    }

    public function testPath()
    {
        $this->assertEquals('/path', $this->uri->getPath());
    }

    public function testHost()
    {
        $this->assertEquals('localhost', $this->uri->getHost());
    }

    public function testGetScheme()
    {
        $this->assertEquals('http', $this->uri->getScheme());
    }

    public function testGetAuthority()
    {
        $uri = clone $this->uri;

        $this->assertEquals('localhost', $uri->getAuthority()); 

        $uri->setUserInfo('wallace');

        $this->assertEquals('wallace@localhost', $uri->getAuthority()); 

        $uri->setUserInfo('wallace', 'my-secret-password');

        $this->assertEquals('wallace:my-secret-password@localhost', $uri->getAuthority()); 

        $this->assertEquals('wallace:my-secret-password@localhost:80', $uri->getAuthority(true)); 

        $uri->setUserInfo('wallacemaxters@gmail.com', 'pass');

        $this->assertEquals(
            'wallacemaxters%40gmail.com:pass@localhost:80', $uri->getAuthority(true)
        );
    }

    public function testGetHostWithPort()
    {
        $this->assertEquals('localhost', $this->uri->getHostWithPort());

        $this->assertEquals('localhost:80', $this->uri->getHostWithPort(true));

        $old_port = $this->uri->getPort();

        $this->uri->setPort(8080);

        $this->assertEquals('localhost:8080', $this->uri->getHostWithPort());

        // normalize to state of setUP

        $this->uri->setPort($old_port);

    }

    public function testGetHostWithScheme()
    {
        $this->assertEquals('http://localhost', $this->uri->GetHostWithScheme());

        $this->assertEquals('http://localhost:80', $this->uri->GetHostWithScheme(true));

        $old_scheme = $this->uri->getScheme();

        $this->uri->setScheme('https');

        $this->assertEquals('https://localhost', $this->uri->GetHostWithScheme());

        $this->assertEquals('https://localhost:443', $this->uri->GetHostWithScheme(true));

        $old_port = $this->uri->getPort();

        $this->uri->setPort(7070);

        $this->assertEquals('https://localhost:7070', $this->uri->getHostWithScheme(false));

        $this->assertEquals('https://localhost:7070', $this->uri->getHostWithScheme(true));

        $this->uri->setScheme($old_scheme)
                  ->setPort($old_port);

    }



    public function testQuery()
    {
        $this->assertEquals(['name' => 'Wallace'], $this->uri->getQueryAsArray());

        $this->assertEquals('Wallace', $this->uri->getQuery()->get('name'));

        $this->assertEquals('name=Wallace', $this->uri->getQueryString());
    }


    public function testBuild()
    {
        $uri = clone $this->uri;

        $uri->setPath('login')
            ->setQueryArray(['redirect' => 1])
            ->setHost('128.0.0.1')
            ->setScheme('ftp')
            ->setPort('22');

        $this->assertEquals(
            'ftp://128.0.0.1:22/login?redirect=1',
            $uri->build()
        );
    }


    public function testCreateFromGlobals()
    {
        $_SERVER['HTTP_HOST'] = 'localhost:8000';

        $_SERVER['SERVER_PORT'] = '8000';

        $_SERVER['REQUEST_URI'] = '/user';

        $_SERVER['QUERY_STRING'] = 'name=Wallace';

        $uri = Uri::createFromGlobals();

        $this->assertEquals('http', $uri->getScheme());

        $this->assertEquals(8000, $uri->getPort());

        $this->assertEquals('/user', $uri->getPath());

        $this->assertEquals(['name' => 'Wallace'], $uri->getQueryAsArray());

        $this->assertEquals('name=Wallace', $uri->getQueryString());

        $this->assertEquals('localhost', $uri->getHost());

        $this->assertEquals('localhost:8000', $uri->getHostWithPort());
    }
}