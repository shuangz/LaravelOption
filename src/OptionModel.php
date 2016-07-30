<?php

namespace Shuangz\Option;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class OptionModel extends Model
{
    use OptionFormater;

    /**
     * id不能赋值
     * @var array
     */
    protected $guarded  = ['id'];


    /**
     * 设置默认的数据表
     * @var string
     */
    protected $table = 'options';

    /**
     * 关闭时间戳功能
     * @var boolean
     */
    public $timestamps = false;


    /**
     * Value值的getter，当value可以反序列时，自动将其变回对象
     * 
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getValueAttribute($value)
    {
        return $this->maybe_unserialize( $value );
    }


    /**
     * Value值的setter，可以序列化时候，保存序列化后的文本
     * 
     * @param string $value [description]
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = $this->maybe_serialize($value);
    }
}
