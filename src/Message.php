<?php

namespace PHPLegends\Http;

/**
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */

class Message
{

	/**
	 * 
	 * @var string
	 * */
	protected $body;

	/**
	 * @var array
	 * */
	protected $headers = [];

	/**
	 * @var string
	 * */
	protected $protocolVersion = '1.1';

	/**
	 * 
	 * 
	 * @param string $body
	 * @param Header|array|null $headers
	 * */
	public function __construct($body, $header = null)
	{
		$this->setBody($body);

		$this->resolveHeaderValue($header);
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
	 * @return Header
	 */
	public function getHeader()
	{
	    return $this->header;
	}

	/**
	 * Resolve value for Header creation
	 * 
	 * @param null|array|\ArrayObject|PHPLegends\Http\Header $header
	 * @return self
	 * @throws \InvalidArgumentException
	 * */
	protected function resolveHeaderValue($header)
	{	

		if ($header === null) {

			return $this->setHeader(new Header([]));

		} elseif (is_array($header)) {

			return $this->setHeaderArray($header);

		} elseif ($header instanceof \ArrayObject)  {

			return $this->setHeaderArray($header->getArrayCopy());

		} elseif ($header instanceof Header) {

			return $this->setHeader($header);
		}

		throw new \InvalidArgumentException('Header is not array or Header object');
	}

	public function setHeaderArray(array $lines)
	{
		return $this->setHeader(new Header($lines));
	}
	
	public function setHeader(Header $header)
	{
		$this->header = $header;

		return $this;
	}

	/**
	 * Gets the value of body.
	 *
	 * @return string
	 */
	public function getBody()
	{
	    return $this->body;
	}

	/**
	 * Sets the value of body.
	 *
	 * @param string $body the body
	 *
	 * @return self
	 */
	public function setBody($body)
	{
	    $this->body = (string) $body;

	    return $this;
	}
	
}