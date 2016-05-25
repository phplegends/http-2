<?php

namespace PHPLegends\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */
class Request extends Message
{

	/**
	 *  @var string | null
	 **/
	protected $requestTarget;

	/**
	 * @var Psr\Http\Message\UriInterface
	 * */
	protected $uri;

	/**
	 * Method of request
	 * 
	 * @var string
	 * */
	protected $method = 'GET';

	/**
	 * @param string $method
	 * @param Psr\Http\Message\UriInterface $uri
	 * @param array $headers
	 * @param Psr\Http\Message\StreamInterface | null $body
	 * @param string $protocolVersion
	 * */
    public function __construct (
    	$method,
    	Uri $uri,
    	$headers = [],
    	$body,
    	$protocolVersion = '1.1'
    ) {

      	parent::__construct($body, $headers);

      	$this->setMethod($method)
        	  ->setUri($uri)
        	  ->setProtocolVersion($protocolVersion);
    }

	/**
	 * @{inheritdoc}
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
	 * @{inheritdoc}
	 * */
	public function getUri()
	{
		return $this->uri;
	}

	public function setUri(Uri $uri)
	{
		$this->uri = $uri;

		return $this;
	}
}