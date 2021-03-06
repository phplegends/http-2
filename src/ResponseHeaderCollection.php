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

    /**
     * @param array $items
     * @param CookieJar|null $cookies
     * */
    public function __construct(array $items = [], CookieJar $cookies = null)
    {
        parent::__construct($items);

        $this->setCookies($cookies ?: new CookieJar);
    }

    /**
     * Gets the value of cookies.
     *
     * @return \PHPLegends\Http\CookieJar
     */
    public function getCookies()
    {
        return $this->cookies;
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

    /**
     * Easy way to create ContentDisposition header format
     * 
     * */
    public function setContentDisposition($filename)
    {
        $this['Content-Disposition'] = sprintf(
            'attachment; filename="%s"', addcslashes($filename, '"\\')
        );

        return $this;
    }
}
