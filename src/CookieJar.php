<?php

namespace PHPLegends\Http;

use PHPLegends\Collections\Collection;

/**
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */
class CookieJar extends Collection
{   
    
    /**
     *
     * @param string $name
     * @param string $value
     * @param array $args
     * */
    public function setCookie($name, $value, array $args = [])
    {
        return parent::set($name, Cookie::create($name, $value, $args));
    }

    /**
     * Add cookie in jar
     * 
     * @param Cookie $cookie
     * @throws \InvalidArgumentException
     * @return self
     * */
    public function add($cookie)
    {
        if ($cookie instanceof Cookie)
        {
            return parent::set($cookie->getName(), $cookie);
        }

        throw new \InvalidArgumentException('argument of add must be Cookie');
    }

    /**
     * Sets the cookie in jar
     * 
     * @param string $name
     * @param string $value
     * */
    public function set($name, $value)
    {
        return $this->setCookie($name, $value);
    }

    /**
     * 
     * @param Cookie[] $items
     * @return self
     * */
    public function setItems(array $items)
    {
        foreach ($items as $cookie) {
            $this->add($cookie);
        }

        return $this;
    }

    public function delete($key)
    {
        if (! $this->has($key)) return null;

        $this->get($key)->invalidate();

        return parent::delete($key);
    }

}
