<?php

namespace PHPLegends\Http;

class Cookie
{

    protected $name;

    protected $value;

    protected $expires;

    protected $path;

    protected $domain;

    protected $secure;

    protected $httpOnly;

    /**
     * 
     * 
     * 
     * 
     * */
    public function __construct($name, $value, $expires = 0, $path = null, $domain = null, $secure = false, $httpOnly = false)
    {
        $this->setName($name);

        $this->setValue($value);

        $this->resolveExpiresValue($expires);

        $this->setPath($path);

        $this->setDomain($domain);

        $this->setSecure($secure);

        $this->setHttpOnly($httpOnly);
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function  setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of value.
     *
     * @param mixed $value the value
     *
     * @return self
     */
    public function  setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the value of expires.
     *
     * @return mixed
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Sets the value of expires.
     *
     * @param mixed $expires the expires
     *
     * @return self
     */
    public function  setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Gets the value of path.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the value of path.
     *
     * @param mixed $path the path
     *
     * @return self
     */
    public function  setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Gets the value of domain.
     *
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Sets the value of domain.
     *
     * @param mixed $domain the domain
     *
     * @return self
     */
    public function  setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Gets the value of secure.
     *
     * @return mixed
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * Sets the value of secure.
     *
     * @param mixed $secure the secure
     *
     * @return self
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * Gets the value of httpOnly.
     *
     * @return mixed
     */
    public function getHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
     * Sets the value of httpOnly.
     *
     * @param mixed $httpOnly the http only
     *
     * @return self
     */
    public function setHttpOnly($httpOnly)
    {
        $this->httpOnly = $httpOnly;

        return $this;
    }

    /**
     * 
     * @param DateTime|int|string $expires
     * @return self
     * @throws \UnexpectedValueException
     * 
     * */
    public function resolveExpiresValue($expires)
    {
        if (is_numeric($expires)) {

            $expires = (int) $expires;

        } elseif (is_string($expires)) {

            $expires = strtotime($expires);

        } elseif ($expires instanceof \DateTime) {

            $expires = $expires->format('U');

        } else {

            throw new \UnexpectedValueException('Unable to process expire value');
        }

        return $this->setExpires($expires);

    }
}