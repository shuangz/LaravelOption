<?php

namespace  Shuangz\Option\Bottle;

use Shuangz\Option\OptionModel;

/**
*
*/
class EloquentBottle implements BottleInterface
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
        return OptionModel::where('name', $name)->pluck('value')->first();
    }

    public function set($name, $value)
    {
        $option        = OptionModel::firstOrNew(['name' => $name]);
        $option->value = $value;
        $option->save();
    }
}