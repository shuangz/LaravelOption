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
	public function update($name, $value);

	/**
	 * 添加一个值
	 *
	 * @param [type] $name  [description]
	 * @param [type] $value [description]
	 * @return mix 原值存在时候返回false，否则返回true
	 */
	public function add($name, $value);

	/**
	 * 删除一个值
	 *
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function delete($name);
}