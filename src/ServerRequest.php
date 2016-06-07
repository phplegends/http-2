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

    /**
     * The object constructor ;)
     * 
     * @param string $method
     * @param Uri | string $uri
     * @param HeaderBag|array $headers
     * @param $parameters
     * @param $body
     * @param 
     * @param string $protocolVersion
     * @param array $serverParams
     * */
    public function __construct (
    	$method,
    	$uri,
    	$header,
    	$protocolVersion = '1.1',
    	$serverParams = []
    ) {

        $this->setMethod($method)
              ->resolveUriValue($uri)
              ->resolveHeaderValue($header)
              ->setProtocolVersion($protocolVersion);

    	$this->serverParams = $serverParams;
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

        $serverRequest = new self(
            $method, $uri, $headers, $protocol, new ParameterCollection($_SERVER)
        );

        $serverRequest
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
        return $this->cookies;
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
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
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
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
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
     * @return mixed
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
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
     * @return mixed
     */
    public function getRawBody()
    {
        return $this->rawBody;
    }

    /**
     * Sets the value of rawBody.
     *
     * @param mixed $rawBody the raw body
     *
     * @return self
     */
    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;

        return $this;
    }
}