<?php

namespace PHPLegends\Http\Exceptions;

use PHPLegends\Http\Response;

/**
 * 
 * Represents a http exception
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */

class HttpException extends \RunTimeException implements HttpExceptionInterface
{
	protected $response;

	public function __construct($message, $statusCode = 500)
	{		
		parent::__construct($message);

		$this->response = new Response($this->getMessage(), $statusCode);
	}

	/**
	 * Get response of exception
	 * 
	 * @return \PHPLegends\Http\Response
	 * */
	public function getResponse()
	{
		return $this->response;
	}
}