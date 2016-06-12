<?php

namespace PHPLegends\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

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
    protected $phrases = [
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
	protected $reasonPhrase = null;

	/**
	 * @var int
	 * */
	protected $statusCode = 200;

	public function __construct($content, $code = 200, $headers = [])
	{
        $this->setStatusCode($code);
                
        parent::__construct($content, $headers);

        if (! $this->getHeaders()->has('Content-Type')) {

            $this->getHeaders()->set('Content-Type', 'text/html; charset=utf-8');
        }
	}

    /**
     * Gets the value of reasonPhrase.
     *
     * @return Psr\Http\Message\StreamInterface
     */
    public function getReasonPhrase()
    {
        $code = $this->getStatusCode();

        if ($this->reasonPhrase === null && isset($this->phrases[$code]))
        {
            return $this->phrases[$code];
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
     *
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

    public function send()
    {
        $this->sendHeaders();

        $this->sendCookies();

        echo $this->getContent();
    }

    public function sendHeaders()
    {
        if (headers_sent()) return false;

        foreach ($this->getHeaders()->getFormated() as $line) {

            header($line, true);
        }

        return true;
    }

    public function sendCookies()
    {
        if (headers_sent()) return false;

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

    public function setCookies(CookieJar $cookies)
    {
        $this->getHeaders()->setCookies($cookies);

        return $this;
    }

    public function getCookies()
    {
        return $this->getHeaders()->getCookies();
    }

    public function setCookie($name, $value, array $args = [])
    {
        return $this->getHeaders()->getCookies()->setCookie($name, $value, $args);
    }
}
