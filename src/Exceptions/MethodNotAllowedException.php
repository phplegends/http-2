<?php

namespace PHPLegends\Http\Exceptions;

use PHPLegends\Http\Request;

/**
 * 
 * Represents a http exception with "not allowed" status
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */

class MethodNotAllowedException extends HttpException 
{
	public function __construct($message = 'Method not allowed')
	{
		parent::__construct($message, 405);
	}

	public static function createFromRequest(Request $request)
	{
        $message = sprintf(
            'Method "%s" not allowed in "%s" path',
            $request->getMethod(),
            $request->getUri()->getPath()
        );

		return new self($message);
	}
}