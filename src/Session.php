<?php

namespace PHPLegends\Http;

use PHPLegends\Session\Session as BaseSession;
use PHPLegends\Session\Handlers\HandlerInterface;
use PHPLegends\Session\Storage;

/**
 * Represents a session
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */
class Session extends BaseSession
{
    /**
     * 
     * @param PHPLegends\Session\Handlers\HandlerInterface $handler
     * @param PHPLegends\Session\Storage $storage
     * @param string $name
     * */
    public function __construct(
        HandlerInterface $handler,
        Storage $storage = null,
        $name = 'SESS_'
    ) { 

        parent::__construct($handler, $name, $storage);
    }

    /**
     * Exports the Cookie used by session
     * 
     * @param array $parameters
     * @return PHPLegends\Http\Cookie
     * */
    public function getCookie(array $parameters = [])
    {
        if (($lifetime = $this->getLifetime()) > 0) {

            $parameters['expires'] = $lifetime + time();
        }

        return Cookie::create($this->getName(), $this->getId(), $parameters);
    }

    /**
     * Close the session 
     * @return void
     * */
    public function close()
    {
        $lifetime = $this->getLifeTime();

        if ($this->gc->passes()) {

            $this->getHandler()->gc($this->gc->getMaxLifetime());
        }

        $this->write();

        $this->closed = true;
    }

}