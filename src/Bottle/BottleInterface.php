<?php

namespace  Shuangz\Option\Bottle;

Interface BottleInterface
{
	/**
	 * 在这个容器中获取一个选项值
	 *
	 * @param  string $name  选项的名称
	 * @return [type]        当没有这个选项时返回null
	 */
	public function get($name);

	/**
	 * 设置设个容器中选项的值
	 *
	 * @param string $name  选项的名称
	 * @param mix    $value 选项的值
	 */
	public function set($name, $value);
}