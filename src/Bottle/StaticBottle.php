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
		return self::$options[$name] ?? null;
	}

	public function update($name, $value)
	{
		self::$options[$name] = $value;
	}

	public function add($name, $value)
	{
		if (isset(self::$options[$name])) {
			return false;
		}
		$this->update($name, $value);
		return true;
	}

	public function delete($name)
	{
		if (isset(self::$options[$name])) {
			unset(self::$options[$name]);
			return true;
		}
		return false;
	}
}