<?php

namespace PHPLegends\Http;

/**
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * @package PHPLegends\Http
 * */

class HeaderBag extends ParameterBag
{
	public function set($name, $value)
	{
		return parent::set($this->normalizeName($name), (array) $value);
	}

	public function get($name, $default = [])
	{
		$name = $this->normalizeName($name);
		
		return array_replace([$name => $default], $this->items)[$name];
	}

	public function has($name)
	{
		return parent::has($this->normalizeName($name));
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

		return null;
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