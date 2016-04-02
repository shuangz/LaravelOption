<?php

namespace Shuangz\Option;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class OptionModel extends Model
{
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
}
