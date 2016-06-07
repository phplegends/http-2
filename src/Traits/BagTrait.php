<?php

namespace PHPLegends\Http\Traits;

trait BagTrait
{
    public function setItems(array $items)
    {
        foreach ($items as $name => $value) {

            $this->set($name, $value);
        }

        return $this;
    }

    public function set($name, $value)
    {
        $this->items[$name] = $value;

        return $this;
    }

    public function get($name, $default = [])
    {
        return $this->has($name) ? $this->items[$name] : $default;
    }

    public function has($name)
    {
        return isset($this->items[$name]);
    }
}