<?php

namespace PHPLegends\Http;

use PHPLegends\Collection\Collection;

class CookieJar extends Collection
{   
    public function setCookie(
        $name, $value, $expires = 0, 
        $path = null, $domain = null, 
        $secure = false, $httpOnly = false
    ) {

        return $this->add(new Cookie($name, $value, $expires, $path, $domain, $secure, $httpOnly));
    }

    public function add($cookie)
    {
        if ($cookie instanceof Cookie)
        {
            return parent::add($cookie);
        }

        throw new \InvalidArgumentException('argument of add must be Cookie');
    }

    public function set($name, $value)
    {
        return $this->setCookie($name, $value);
    }

}