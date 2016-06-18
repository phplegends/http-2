<?php

namespace PHPLegends\Http;

/**
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * @package PHPLegends\Http
 * */

class HeaderCollection extends ParameterCollection
{	
	/**
	 * Sets an value for header
	 * 
	 * @param string $name
	 * @param string|array $value
	 * @return self
	 **/
	public function set($name, $value)
	{
		return parent::set($this->normalizeName($name), (array) $value);
	}

	/**
	 * 
	 * @param array $items
	 * @return self
	 * */
	public function setItems(array $items)
	{
		foreach ($items as $name => $value) {

			$this->set($name, $value);
			
		}

		return $this;
	}

	public function get($name, $default = [])
	{
		$name = $this->normalizeName($name);
		
		return array_replace([$name => $default], $this->all())[$name];
	}

	public function has($name)
	{
		return parent::has($this->normalizeName($name));
	}

	/**
	 * Normalize the header name
	 * 
	 * @param string $name
	 * @return string
	 * */
	protected static function normalizeName($name)
	{
		return mb_convert_case($name, MB_CASE_TITLE);
	}

	/**
	 * Get the line for header item name.
	 * 
	 * @param string $name
	 * @return string|null
	 * */
	public function getLine($name)
	{
		if ($this->has($name))
		{
			return implode(', ', $this->get($name));
		}

		return null;
	}

	/**
	 * 
	 * Get all line of headers formated
	 * @return array
	 * */
	public function getFormated()
	{
		$items = [];

		foreach (array_keys($this->items) as $name) {

			$items[] = sprintf('%s: %s', $name, $this->getLine($name));
		}

		return $items;
	}
}