<?php

namespace  Shuangz\Option\Bottle;

/**
*
*/
class StaticBottle implements BottleInterface
{

	/**
	 * 保存已经载入的选项
	 *
	 * @static
	 * @var array
	 */
	static protected $options = [] ;

	public function get($name)
	{
		return $this->options[$name] ?? null;
	}

	public function set($name, $value)
	{
		$this->options[$name] = $value;
	}
}