<?php

namespace PHPLegends\Http\Exceptions;


/**
 * 
 * Represents a http exception with not found status
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */

class NotFoundException extends HttpException 
{
	public function __construct($message)
	{
		parent::__construct($message, 404);
	}
}