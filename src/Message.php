<?php

namespace PHPLegends\Http;

/**
 * Abstract class with common methods for Request and Response
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */
abstract class Message
{

	/**
	 * 
	 * @var string
	 * */
	protected $content;

	/**
	 * @var HeaderCollection
	 * */
	protected $headers;

	/**
	 * @var string
	 * */
	protected $protocolVersion = '1.1';

	/**
	 * 
	 * 
	 * @param string $content
	 * @param Header|array|null $headers
	 * */
	public function __construct($content, $headers = null)
	{
		$this->setContent($content);

		$this->resolveHeaderValue($headers);
	}

	/**
	 * Gets the protocol version of HTTP
	 * 
	 * @return string
	 */
	public function getProtocolVersion()
	{
	    return $this->protocolVersion;
	}

	/**
	 * Sets the value of protocolVersion.
	 *
	 * @param string|float $protocolVersion the protocol version
	 *
	 * @return self
	 */
	public function setProtocolVersion($protocolVersion)
	{
	    $this->protocolVersion = (string) $protocolVersion;

	    return $this;
	}

	/**
	 * Resolve value for HeaderBag creation
	 * 
	 * @param null|array|\ArrayObject|PHPLegends\Http\Header $header
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

	/**
	 * 
	 * @param HeaderCollection $header 
	 * */
	public function setHeaders(HeaderCollection $headers)
	{
		$this->headers = $headers;

		return $this;
	}

	/**
	 * 
	 * @return HeaderCollection
	 * */

	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Gets the value of body.
	 *
	 * @return string
	 */
	public function getContent()
	{
	    return $this->content;
	}

	/**
	 * Sets the value of body.
	 *
	 * @param string $body the body
	 *
	 * @return self
	 */
	public function setContent($content)
	{
	    $this->content = (string) $content;

	    return $this;
	}
	
}