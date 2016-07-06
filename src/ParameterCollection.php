<?php

namespace PHPLegends\Http;

use PHPLegends\Collections\Collection;

/**
 * Represents a collection for Parameter
 *
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */

class ParameterCollection extends Collection
{
    /**
     *
     * @param string $key
     * @param string $default
     * @return string
     * */
    public function getAsString($key, $default = '')
    {
        $value = $this->getOrDefault($key, $default);

        if (is_array($value)) {
            $this->throwExceptionForInvalidType($key, 'string');
        }

        return (string) $value;
    }

    /**
     *
     * @param string
     * @param boolean $default
     * @return boolean
     * */
    public function getAsBoolean($key, $default = false)
    {
        return filter_var($this->getOrDefault($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    public function getAsArray($key, array $default = [])
    {
        return (array) $this->getOrDefault($key, $default);
    }

    public function getAsInt($key, $default = 0)
    {
        $value = $this->getOrDefault($key, $default);

        if (is_array($value)) {

            $this->throwExceptionForInvalidType($key, 'int');
        }

        return (int) $value;
    }

    public function getAsFloat($key, $default = 0)
    {
        $value = $this->getOrDefault($key, $default);

        if (is_array($value)) {

            $this->throwExceptionForInvalidType($key, 'integer');
        }

        return (float) $value;
    }

    public function isEmptyOrNullString($key)
    {
        $value = $this->getOrDefault($key);

        return null === $value || is_string($value) && $value === '';
    }

    protected function throwExceptionForInvalidType($key, $typeExpected)
    {
        $message = sprintf(
            'The item "%s" must be of "%s" type, "%s" given',
            $key,
            $typeExpected,
            gettype($this->getOrDefault($key))
        );

        throw new \UnexpectedValueException($message);
    }
}
