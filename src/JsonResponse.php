<?php

namespace PHPLegends\Http;


class JsonResponse extends Response
{
	public function __construct($content, $code = 200, $options = 0, array $headers = [])
	{
		parent::__construct(json_encode($content, $options), $code, $headers);
		
		$this->setHeader('Content-Type', 'application/json');
	}
}