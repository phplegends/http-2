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
	 * @param Uri|string $uri
	 * @param string $body
	 * */
    public function __construct ($method, $uri, $body = '') {

      	parent::__construct($body);

      	$this->setMethod($method)->resolveUriValue($uri);
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
}