<?php

namespace PHPLegends\Http;

/**
 * Represents the json response
 * 
 * @author Wallace de Souza Vizerra
 * */
class JsonResponse extends Response
{   
    
    /**
     * 
     * 
     * @param mixed $content
     * @param int $code
     * @param int $options
     * @param mixed $headers
     * */
	public function __construct($content, $code = 200, $options = 0, $headers = [])
	{
		parent::__construct(json_encode($content, $options), $code, $headers);
		
		$this->getHeaders()->set('Content-Type', 'application/json');
	}

    /**
     * Overwrites the 'SetContent' 
     * 
     * @uses json_encode
     * @param mixed $content
     * @return self
     * */
    public function setContent($content)
    {
        return parent::setContent(json_encode($content));
    }
}