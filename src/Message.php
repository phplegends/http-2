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
	 * @var string
	 * */
	protected $protocolVersion = '1.1';

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