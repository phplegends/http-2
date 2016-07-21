<?php

namespace PHPLegends\Http;

/**
 * Represents the HTTP Response
 *
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 *
 * */
class Response extends Message
{

    /**
     *
     * @var array
    */
    protected static $phrases = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

	/**
	 *
	 * @var string
	 * */
	protected $reasonPhrase;

	/**
	 * @var int
	 * */
	protected $statusCode = 200;

    /**
     *
     * @var ResponseHeaderCollection
     * */

    protected $headers;

	public function __construct($content, $code = 200, $headers = [])
	{
        $this->setStatusCode($code);

        $this->setContent($content);

        $this->resolveHeaderValue($headers);
	}

    /**
     * Gets the value of reasonPhrase.
     *
     * @return Psr\Http\Message\StreamInterface
     */
    public function getReasonPhrase()
    {
        $code = $this->getStatusCode();

        if ($this->reasonPhrase === null && isset(static::$phrases[$code]))
        {
            return static::$phrases[$code];
        }

        return (string) $this->reasonPhrase;
    }

    /**
     * Sets the value of reasonPhrase.
     *
     * @param string $reasonPhrase the reason phrase
     *
     * @return self
     */
    public function setReasonPhrase($reasonPhrase)
    {
        $this->reasonPhrase = $reasonPhrase;

        return $this;
    }

    /**
     * Gets the value of statusCode.
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param int $statusCode the status code
     * @return self
     */
    public function setStatusCode($statusCode)
    {
    	if (! is_int($statusCode))
    	{
    		throw new \InvalidArgumentException('The statusCode MUST BE integer');
    	}

        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Send
     *
     * @param boolean $force
     * @return void
     * */
    public function send($force = false)
    {
        $this->sendHeaders($force);

        $this->sendCookies($force);

        echo $this->getContent();
    }

    /**
     *
     * @param boolean $force
     * @return void
     * */
    public function sendHeaders($force = false)
    {
        if (headers_sent() && ! $force) return false;

        header(sprintf('HTTP/%s %s %s', 
            $this->getProtocolVersion(),
            $this->getStatusCode(),
            $this->getReasonPhrase()
        ));

        foreach ($this->getHeaders()->getFormatted() as $line) {

            @header($line, true);
        }

        return true;
    }

    /**
     * Send the cookies
     *
     * @param boolean $force
     * @return void
     * */
    public function sendCookies($force = false)
    {
        if (headers_sent() && ! $force) return false;

        foreach ($this->getHeaders()->getCookies() as $cookie) {

            setcookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpires(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->getSecure(),
                $cookie->getHttpOnly()
            );
        }

    }

    /**
     *
     * @param \PHPLegends\Http\CookieJar $cookies
     * @return self
     * */
    public function setCookies(CookieJar $cookies)
    {
        $this->getHeaders()->setCookies($cookies);

        return $this;
    }

    /**
     *
     * @return \PHPLegends\Http\CookieJar
     * */
    public function getCookies()
    {
        return $this->getHeaders()->getCookies();
    }

    /**
     *
     *
     * @param string $name
     * @param string $valu
     * @param array $args
     * @return \PHPLegends\Http\Cookie
     * */
    public function setCookie($name, $value, array $args = [])
    {
        return $this->getHeaders()->getCookies()->setCookie($name, $value, $args);
    }

    /**
     *
     *
     * @param \PHPLegends\Http\ResponseHeaderCollection $headers
     * @return self
     * */
    public function setHeaders(ResponseHeaderCollection $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     *
     * @return \PHPLegends\Http\ResponseHeaderCollection
     * */
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

            $headers = new ResponseHeaderCollection;

        } elseif (is_array($headers)) {

            $headers = new ResponseHeaderCollection($headers);

        } elseif (! $headers instanceof ResponseHeaderCollection) {

            throw new \InvalidArgumentException('Header is not array or Header object');

        }

        return $this->setHeaders($headers);
    }
}
