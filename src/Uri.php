<?php

namespace PHPLegends\Http;

/**
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */

class Uri
{
    /**
     * 
     * @var array
     * */
    protected $components = [
        'host'     => '',
        'port'     => NULL,
        'query'    => '',
        'fragment' => '',
        'scheme'   => '',
        'user'     => '',
        'pass'     => '',
        'path'     => ''
    ];

    /**
     * 
     * 
     * @param string $uri
    */
	public function __construct($uri = '')
	{
        $parts = parse_url($uri);

        if (! is_array($parts))
        {
            throw new \InvalidArgumentException("Unable to parse url '{$uri}'");  
        }

        $this->components = $parts + $this->components;
	}

    /**
     * @return string
     * */
	public function getQueryString()
	{
		return $this->components['query'];
	}

    /**
     * Get parsed query string
     * 
     * @return array
     * */
    public function getQueryAsArray()
    {
        parse_str($this->getQueryString(), $query);

        return $query;
    }

    /**
     * Get a parameter bag for Query String
     * 
     * @return ParameterCollection
     * */
    public function getQuery()
    {
        return new ParameterCollection($this->getQueryAsArray());
    }

    /**
     * @return int|null
     * */
    public function getPort()
    {
        return $this->components['port'];
    }

    /**
     * Get authority for url
     * 
     * @return string
     * */
    public function getAuthority()
    {
        $authority = '';

        if ($userinfo = $this->getUserInfo()) {
            $authority .= $userinfo . '@';
        }

        $authority .= $this->getHost();

        if ($port = $this->getPort())
        {
            $authority .= ':' . $port;
        }

        return $authority;
    }

    /**
     * @return string
     * */
    public function getHost()
    {
        return $this->components['host'];
    }

    /**
     * @return string
     * */
    public function getPath()
    {
        return $this->components['path'];
    }

    /**
     * @return string
     * */
    public function getFragment()
    {
        return $this->components['fragment'];
    }

    /**
     *
     * @param string $scheme
     * @return self
     * */
    public function setScheme($scheme)
    {
        if (! is_string($scheme)) {
            throw new \InvalidArgumentException('Invalid scheme');
        }

        $scheme = static::normalizeScheme($scheme);

        $this->components['scheme'] = $scheme;

        return $this;
    }
    /**
     * @return string
     * */
    public function getUserInfo()
    {
        $userinfo = '';

        if ($this->components['user'])
        {
            $userinfo = $this->components['user'];
        }

        if ($this->components['pass'])
        {
            $userinfo .= ':' . $this->components['pass'];
        }

        return $userinfo;
    }

    /**
     * @param int|null $port
     * @throws \InvalidArgumentException
     * */
    public function setPort($port)
    {
        if (preg_match('/^\d+$/', $port) > 0 || is_null($port)) {

            return $this->components['port'] = $port;
        }

        throw new \InvalidArgumentException('Invalid port');
    }

    /**
     * @param string $host
     * @return self
     * */
    public function setHost($host)
    {        
        if (! is_string($host))
        {
            throw new \InvalidArgumentException('Invalid host');
        }

        $this->host = $host;

        return $this;
    }

    /**
     * @param string $user
     * @param string|null $password
     * */
    public function setUserInfo($user, $password = null)
    {
        $this->components = ['user' => $user, 'pass' => $password] + $this->components;

        return $this;
    }

    /**
     * @param string $query
     * */
    public function setQueryString($query)
    {
        if (! is_string($query)) {

            throw new \InvalidArgumentException('Invalid query, only query or string');
        }

        $this->components['query'] = $query;

        return $this;
    }

    /**
     * Define query string for Uri using array
     * 
     * @param array $queryParams
     * @return self
     * */
    public function setQueryArray(array $query)
    {
        $query = http_build_query($queryParams);

        return $this->setQueryString($query);
    }

    /**
     * @param string|null $fragment
     * */
    public function setFragment($fragment)
    {

        if (! is_string($fragment))
        {
            throw new \InvalidArgumentException('Invalid fragment value');
        }

        $this->components['fragment'] = $fragment;

        return $this;
    }

    /**
     * Sets the path value
     * 
     * @param string $path
     * @return self
     * */
    public function setPath($path)
    {
        if ( ! is_string($path)) {

            throw new \InvalidArgumentException('Invalid path value');
        }

        $this->components['path'] = $path;

        return $this;
    }

    /**
     * @{inheritdoc}
     * */
    public function getScheme()
    {
        return $this->components['scheme'];
    }


    /**
     * @return string
     * */
    public function __toString()
    {
        return $this->build();
    }

    /**
     * Builds the uri into string
     * 
     * @return string
     * */
    public function build()
    {
        $uri = '';

        $c = $this->components;

        if ($c['scheme']) {
            $uri .= $c['scheme'] . '://';
        }

        if ($authority = $this->getAuthority()) {

            $uri .= $authority;
        }

        if ($c['path']) {
            $uri .= '/' . $c['path'];
        }

        if ($c['query']) {
            $uri .= '?' . $c['scheme'];
        }

        if ($c['fragment']) {
            $uri .= '#' .$c['fragment'];
        }

        return $uri;

    }

    /**
     * Normalize scheme 
     * 
     * @param string $scheme
     * @return string
     * */
    protected static function normalizeScheme($scheme)
    {
        return strtolower(rtrim($scheme, ':/'));
    }

}