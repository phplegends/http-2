<?php

namespace PHPLegends\Http;

/**
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */

abstract class Message
{

	/**
	 * 
	 * @var string
	 * */
	protected $body;

	/**
	 * @var HeaderCollection
	 * */
	protected $headerBag;

	/**
	 * @var string
	 * */
	protected $protocolVersion = '1.1';

	/**
	 * 
	 * 
	 * @param string $contents
	 * @param Header|array|null $headers
	 * */
	public function __construct($contents, $headers = null)
	{
		$this->setContents($body);

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
	 * @return HeaderBag
	 */
	public function getHeaderBag()
	{
	    return $this->headerBag;
	}

	/**
	 * Resolve value for HeaderBag creation
	 * 
	 * @param null|array|\ArrayObject|PHPLegends\Http\Header $header
	 * @return self
	 * @throws \InvalidArgumentException
	 * */
	protected function resolveHeaderValue($header)
	{	

		if ($header === null) {

			return $this->setHeaders([]);

		} elseif (is_array($header)) {

			return $this->setHeaders($header);

		} elseif ($header instanceof \ArrayObject)  {

			return $this->setHeaders($header->getArrayCopy());

		} elseif ($header instanceof Header) {

			return $this->setHeaderBag($header);
		}

		throw new \InvalidArgumentException('Header is not array or Header object');
	}

	/**
	 * 
	 * @param array 
	 * */
	public function setHeaders(array $lines)
	{
		return $this->setHeaderBag(new HeaderBag($lines));
	}
	
	public function setHeaderBag(HeaderBag $headerBag)
	{
		$this->headerBag = $headerBag;

		return $this;
	}

	/**
	 * Gets the value of body.
	 *
	 * @return string
	 */
	public function getContents()
	{
	    return $this->contents;
	}

	/**
	 * Sets the value of body.
	 *
	 * @param string $body the body
	 *
	 * @return self
	 */
	public function setContents($contents)
	{
	    $this->contents = (string) $contents;

	    return $this;
	}
	
}