<?php

namespace PHPLegends\Http;

/**
 * Represents the redirect responses
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */
class Redirector extends Response
{
	public function __construct($location, $code = 302, array $headers = [])
	{
		$this->setHeader('Location', $location);

		$this->setStatusCode($code);

		$headers && $this->setHeaders($headers);
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
	}
}