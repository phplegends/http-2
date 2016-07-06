<?php

namespace PHPLegends\Http;

/**
 * Represents the redirect responses
 *
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */

class RedirectResponse extends Response
{
	public function __construct($location, $code = 302, $headers = [])
	{
		$headers = ['Location' => (string) $location] + $headers;

		$this->setStatusCode($code);

		$this->resolveHeaderValue($headers);
	}

	/**
	 *
	 * @throws \BadMethodCallException
	 * */
	public function setContent($content)
	{
		throw new \BadMethodCallException('Cannot set content in redirect response');
	}

	/**
	 * In redirector response, the method send cannot 'echos' output
	 * @return void
	 * */
	public function send()
	{
		if (headers_sent()) {

			throw new \RunTimeException('Cannot redirect after output contents');
		}

		$this->sendHeaders();
		$this->sendCookies();
	}

	/**
	 *
	 * @return string
	 * */
	public function getLocation()
	{
		return $this->getHeaders()->getLine('Location');
	}
}
