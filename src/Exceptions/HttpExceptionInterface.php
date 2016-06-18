<?php

namespace PHPLegends\Http\Exceptions;

interface HttpExceptionInterface
{	
	/**
	 * @return PHPLegends\Http\Response
	 * */
	public function getResponse();
}