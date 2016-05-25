<?php

namespace PHPLegends\Http;

/**
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * @package PHPLegends\Http
 * */

class Header implements ArrayAccess
{
	protected $items = [];

	public function __construct(array $items)
	{
		$items && $this->setItems($items);
	}

	public function setItems(array $items)
	{
		foreach ($items as $name => $value) {

			$this->set($name, $value);
		}

		return $this;
	}

	public function set($name, $value)
	{
		$this->items[$this->normalizeName($name)] = (array) $value;

		return $this;
	}

	public function get($name, $default = [])
	{
		$name = $this->normalizeName($name);

		return $this->has($name) ? $this->items[$name] : $default;
	}

	public function has($name)
	{
		return isset($this->items[$this->normalizeName($name)]);
	}

	protected static function normalizeName($name)
	{
		return mb_convert_case($name, MB_CASE_TITLE);
	}

	public function getLine($name)
	{
		if ($this->has($name))
		{
			return implode(', ', $this->get($name));
		}
	}


	public function offsetSet($key)
	{
		return $this->set($key, $value);
	}

	public function offsetExists($key)
	{
		return $this->has($key);
	}

	public function offsetUnset($key)
	{
		$this->remove($key);
	}

	public function offsetGet($key)
	{
		return $this->get($key, []);
	}

	public function remove($key)
	{
		$value = $this->get($key);

		unset($this->items[$key]);

		return $value;
	}

	public function getLineList()
	{
		$items = [];

		foreach (array_keys($this->items) as $name) {

			$items[] = sprintf('%s: %s', $name, $this->getLine($name));
		}

		return $items;
	}

}