<?php

namespace PHPLegends\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

/**
 *
 * Represents a Server Request.
 *
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 *
 * */
class Request extends Message
{

    /**
     * Uri instance
     *
     * @var Uri
     * */
    protected $uri;

    /**
     * Method of request
     *
     * @var string
     * */
    protected $method = 'GET';

    /**
     * Server variable
     *
     * @var ParameterCollection
     * */
	protected $server;

    /**
     * Cookies
     *
     * @var ParameterCollection
     * */
	protected $cookies;

    /**
     * Query String variable
     *
     * @var ParameterCollection
     * */
	protected $query;

    /**
     * Post attributes variable
     *
     * @var ParameterCollection
     * */
    protected $body;

    /**
     * Uploaded files variable
     *
     * @var ParameterCollection
     * */
    protected $uploadedFiles;

    public function __construct ($method, $uri, $headers = null) {

        $this->setMethod($method);

        $this->resolveUriValue($uri);

        $this->resolveHeaderValue($headers);

        if ($this->getUri()->getQueryString()) {

            $this->setQuery($this->getUri()->getQuery());
        }
    }

    public static function createFromGlobals()
    {

        $method = 'GET';

        $content = null;

        $protocol = '1.1';

        if (isset($_SERVER['REQUEST_METHOD'])) {

            $method = $_SERVER['REQUEST_METHOD'];
        }

        $headers = array_map(function ($value) {
            
            return array_map('trim', explode(',', $value));

        }, \PHPLegends\Http\get_all_headers());

        if (in_array($method, ['POST', 'PUT'])) {

            $content = file_get_contents('php://input');
        }

        if (isset($_SERVER['SERVER_PORT'])) {

            $protocol = str_replace('HTTP/', '', $_SERVER['SERVER_PORT']);
        }

        $uri = Uri::createFromGlobals();

        $request = new self($method, $uri, $headers);

        $request->setQuery(new ParameterCollection($_GET))
                ->setBody(new ParameterCollection($_POST))
                ->setCookies(new ParameterCollection($_COOKIE))
                ->setUploadedFiles(FilesCollection::createFromArray($_FILES))
                ->setServer(new ParameterCollection($_SERVER))
                ->setContent($content);

        return $request;
    }

    public static function createUriFromGlobals()
    {

        $uri = new Uri();

        if (isset($_SERVER['HTTPS'])) {

            $uri->setScheme($_SERVER['HTTPS'] == 'on' ? 'https' : 'http');
        }

        if (isset($_SERVER['HTTP_HOST'])) {

            $uri->setHost($_SERVER['HTTP_HOST']);

        } elseif (isset($_SERVER['SERVER_NAME'])) {

            $uri->setHost($_SERVER['SERVER_NAME']);
        }

        if (isset($_SERVER['SERVER_PORT'])) {

            $uri->setPort($_SERVER['SERVER_PORT']);
        }

        if (isset($_SERVER['REQUEST_URI'])) {

            $uri->setPath(strtok($_SERVER['REQUEST_URI'], '?'));
        }

        if (isset($_SERVER['QUERY_STRING'])) {

            $uri->setQueryString($_SERVER['QUERY_STRING']);
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
        return $this->cookies ?: $this->cookies = new ParameterCollection;
    }

    /**
     * Sets the value of cookie.
     *
     * @param mixed $cookies the cookie
     *
     * @return self
     */
    public function setCookies(ParameterCollection $cookies)
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
     * @return FilesCollection
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles ?: $this->uploadedFiles = new FilesCollection;
    }

    /**
     * Sets the value of uploadedFiles.
     *
     * @param mixed $uploadedFiles the uploaded files
     *
     * @return self
     */
    public function setUploadedFiles(FilesCollection $uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return $this;
    }

    public function getJsonContent()
    {

        $data = json_decode($this->getContent(), true);

        if (json_last_error() === JSON_ERROR_NONE) {

            return $data;
        }

        $message = 'Invalid Json data';

        // Essa função só existe a partir do php 5.5

        if (function_exists('json_last_error_msg')) {

            $message = json_last_error_msg();

        }

        throw new \RunTimeException("Json error: {$message}");
    }

    public function isXhr()
    {
        return strtolower($this->getHeaders()->getLine('x-requested-with')) == 'xmlhttprequest';
    }

    /**
     *
     * @return string
     * */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     *
     * @param string $method
     * @return self
     *
     * */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     *
     * @param string $method
     * @return boolean
     * */
    public function isMethod($method)
    {
        return strtoupper($method) === $this->getMethod();
    }

    /**
     * Gets the Uri
     *
     * @return Uri
     * */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Sets the Uri
     *
     * @param Uri $uri
     * @return self
     * */
    public function setUri(Uri $uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Resolves the value for Uri
     *
     * @param string|Uri $uri
     * @return self
     * */
    protected function resolveUriValue($uri)
    {
        if (is_string($uri))
        {
            $uri = new Uri($uri);

        } elseif (! $uri instanceof Uri) {

            throw new \UnexpectedValueException('Invalid value for URI');
        }

        return $this->setUri($uri);
    }

    public function setHeaders(HeaderCollection $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * Resolve value for ResponseHeaderCollection creation
     *
     * @param null|array|\PHPLegends\Http\Header $header
     * @return self
     * @throws \InvalidArgumentException
     * */
    protected function resolveHeaderValue($headers)
    {

        if ($headers === null) {

            $headers = new HeaderCollection;

        } elseif (is_array($headers)) {

            $headers = new HeaderCollection($headers);

        } elseif (! $headers instanceof HeaderCollection) {

            throw new \InvalidArgumentException('Header is not array or Header object');

        }

        return $this->setHeaders($headers);
    }
}
