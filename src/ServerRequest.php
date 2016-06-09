<?php

namespace PHPLegends\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

/**
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */
class ServerRequest extends Request
{
	protected $server;

	protected $cookies;

	protected $query;

    protected $body;
    
    protected $uploadedFiles;

    protected $rawBody = null;

    public function __construct($method, $uri, ParameterCollection $server) {

        $this->setMethod($method)
              ->resolveUriValue($uri)
              ->setServer($server);


        if ($this->getUri()->getQueryString()) {

            $this->setQuery($this->getUri()->getQuery());
        }
    }

    
    /**
     * Normalizes the value of uploadedFiles.
     *
     * @param mixed $uploadedFiles the uploaded files
     *
     * @return self
     */
    protected static function normalizeUploadedFiles(array $uploadedFiles)
    {
        $files = [];

        foreach ($uploadedFiles as $key => $value) {

            if ($value instanceof UploadedFileInterface) {

                $files[$key] = $value;

            } elseif (is_array($value) && isset($value['tmp_name'])) {

                $files[$key] = static::createUploadedFileFromSpec($value);

            } elseif (is_array($value)) {
                
                $files[$key]  = static::normalizeUploadedFiles($value);

            } else {

                throw new \InvalidArgumentException('Invalid uploaded files value');
            }
        }

        return $files;
    }

    protected static function createUploadedFileFromSpec(array $value)
    {
        if (is_array($value['tmp_name']))
        {
            return static::normalizeUploadedFiles($value);
        }

        return new UploadedFile(
            $value['tmp_name'], 
            $value['name'], 
            $value['size'],
            $value['error'],
            $value['type']
        );
    }

    public static function createFromGlobals()
    {

        $method = 'GET';
        $rawBody = null;
        $protocol = '1.1';

        if (isset($_SERVER['REQUEST_METHOD'])) {

            $method = $_SERVER['REQUEST_METHOD'];
        }

        $headers = \PHPLegends\Http\get_all_headers();

        if (! in_array($method, ['GET', 'POST'])) {
            $rawBody = file_get_contents('php://input');
        }

        if (isset($_SERVER['SERVER_PORT'])) {

            $protocol = str_replace('HTTP/', '', $_SERVER['SERVER_PORT']);
        }

        $uri = static::createUriFromGlobals();

        $request = new self(
            $method, $uri, new ParameterCollection($_SERVER)
        );

        $request
            ->setQuery(new ParameterCollection($_GET))
            ->setBody(new ParameterCollection($_POST))
            ->setCookies(new CookieJar($_COOKIE))
            ->setUploadedFiles(
                new ParameterCollection(
                    static::normalizeUploadedFiles($_FILES)
                )
            )
            ->setRawBody($rawBody);

        return $request;
    }

    public static function createUriFromGlobals()
    {

        $uri = new Uri();

        if (isset($_SERVER['HTTPS'])) {

            $uri = $uri->setScheme($_SERVER['HTTPS'] == 'on' ? 'https' : 'http');
        }

        if (isset($_SERVER['HTTP_HOST'])) {

            $uri = $uri->setHost($_SERVER['HTTP_HOST']);

        } elseif (isset($_SERVER['SERVER_NAME'])) {

            $uri = $uri->setHost($_SERVER['SERVER_NAME']);
        }

        if (isset($_SERVER['SERVER_PORT'])) {

            $uri = $uri->setPort($_SERVER['SERVER_PORT']);
        }

        if (isset($_SERVER['REQUEST_URI'])) {

            $uri = $uri->setPath(strtok($_SERVER['REQUEST_URI'], '?'));
        }

        if (isset($_SERVER['QUERY_STRING'])) {

            $uri = $uri->setQuery($_SERVER['QUERY_STRING']);
        }
        
        return $uri;
    }

    public function isSecure()
    {
        return $this->getUri()->getScheme() === 'https';
    }

    /**
     * Gets the value of server.
     *
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Sets the value of server.
     *
     * @param mixed $server the server
     *
     * @return self
     */
    public function setServer(ParameterCollection $server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Gets the value of cookies.
     *
     * @return mixed
     */
    public function getCookies()
    {
        return $this->cookies ?: $this->cookies = new CookieJar;
    }

    /**
     * Sets the value of cookie.
     *
     * @param mixed $cookies the cookie
     *
     * @return self
     */
    public function setCookies(CookieJar $cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * Gets the value of query.
     *
     * @return \PHPLegends\Http\ParameterCollection
     */
    public function getQuery()
    {
        return $this->query ?: $this->query = new ParameterCollection;
    }

    /**
     * Sets the value of query.
     *
     * @param mixed $query the query
     *
     * @return self
     */
    public function setQuery(ParameterCollection $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Gets the value of body.
     *
     * @return ParameterCollection
     */
    public function getBody()
    {
        return $this->body ?: $this->body = new ParameterCollection;
    }

    /**
     * Sets the value of body.
     *
     * @param mixed $body the body
     *
     * @return self
     */
    public function setBody(ParameterCollection $body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Gets the value of uploadedFiles.
     *
     * @return ParameterCollection
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles ?: $this->uploadedFiles = new ParameterCollection;
    }

    /**
     * Sets the value of uploadedFiles.
     *
     * @param mixed $uploadedFiles the uploaded files
     *
     * @return self
     */
    public function setUploadedFiles(ParameterCollection $uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return $this;
    }

    /**
     * Gets the value of rawBody.
     *
     * @return string|null
     */
    public function getRawBody()
    {
        return $this->rawBody;
    }

    /**
     * Sets the value of rawBody.
     *
     * @param string|null $rawBody the raw body
     *
     * @return self
     */
    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;

        return $this;
    }
}