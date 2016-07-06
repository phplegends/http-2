<?php

namespace PHPLegends\Http;

/**
 *
 *
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * @package PHPLegends\Http
 * */

class ResponseHeaderCollection extends HeaderCollection
{
    /**
     *
     * @var \PHPLegends\Http\CookieJar
     * */
    protected $cookies;

    public function __construct(array $items = [], CookieJar $cookies = null)
    {
        parent::__construct($items);

        $cookies && $this->setCookies($cookies);
    }

    /**
     * Gets the value of cookies.
     *
     * @return \PHPLegends\Http\CookieJar
     */
    public function getCookies()
    {
        return $this->cookies ?: $this->cookies = new CookieJar;
    }

    /**
     * Sets the value of cookies.
     *
     * @param \PHPLegends\Http\CookieJar $cookies the cookies
     *
     * @return self
     */
    public function setCookies(CookieJar $cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }
}
