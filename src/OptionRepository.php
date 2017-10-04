<?php

namespace Shuangz\Option;

use Shuangz\Option\BottleBus;


/**
 * 该类只会实例化一次
 */
class OptionRepository{

    protected $bus;

    public function __construct(BottleBus $bus)
    {
        $this->bus = $bus;
    }
    /**
     * 获取一个option
     *
     * @param  [type]  $option_name  [description]
     * @param  boolean $default      [description]
     * @return [type]                [description]
     */
    public function get($name, $default = null )
    {
        $value = $this->bus->get($name);
        return $value == null ? $default : $value ;
    }

    /**
     * 更新一个 option 如果不存在则新建并设置该值
     *
     * @param  [type]  $name     [description]
     * @param  [type]  $value    [description]
     * @param  integer $autoload [description]
     * @return [type]            [description]
     */
    public function update($name, $value, $autoload = false)
    {
        return $this->bus->set($name, $value);
    }

    /**
     * 增加一个option，如果已经存在则返回false
     *
     * @param 字符串    $name      需要增加的选项名称
     * @param 混合类型  $value     选项的值
     * @param 整数      $autoload  是否在实例化该类时自动加载，1表示“是”，0表示“否”
     */
    public function add($name, $value, $autoload = 0)
    {
        $name  = $this->sanitize_key($name);

        $option = OptionModel::firstOrNew(['name' => $name]);

        if ($option->exists) {

            return false;

        } else {

            $option->value    = $value;
            $option->autoload = $autoload;
            $option->save();

            $this->cacheValue($name, $value);

            return true;

        }
    }
    /**
     * 删除一个选项
     * @param  字符串 $name 需要删除的选项名称
     * @return 布尔值       返回是否删除成功
     */
    public function delete($name)
    {
        //TODO:删除一个选项
    }
}
