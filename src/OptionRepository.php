<?php

namespace Shuangz\Option;

use Illuminate\Support\Facades\Cache;

/**
 * 该类只会实例化一次
 */
class OptionRepository{


    /**
     * 已经从数据中取出的选项，相当于一个缓存。
     * 下次获取时可以从这里获取
     * @var array
     */
    protected $autoload = array();


    public function __construct()
    {
        $this->loadAutoOptions();
    }

    /**
     * 构造时加载自动获取的选项
     * @return [type] [description]
     */
    public function loadAutoOptions()
    {
        $collection  = OptionModel::where('autoload', '=', 1)->pluck('value', 'name');

        $this->autoload = $collection->all();
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

        $name = $this->sanitize_key( $name );

        if ( empty( $name ) ) {
            return false;
        }

        if (isset($this->autoload[$name])) {
            return $this->autoload[$name];
        } else {

            $value = Cache::remember($this->cachePrefix().$name, 60, function() use ($name) {
                            return OptionModel::where('name', $name)->pluck('value')->first();
                     });

            if ($value == null)
            {
                return $default;
            }

            $this->autoload[$name] = $value;
            return $value;
        }
    }

    /**
     * 更新一个 option 如果不存在则新建并设置该值
     *
     * @param  [type]  $name     [description]
     * @param  [type]  $value    [description]
     * @param  integer $autoload [description]
     * @return [type]            [description]
     */
    public function update($name, $value, $autoload = 0)
    {
        $name  = $this->sanitize_key($name);

        $option = OptionModel::firstOrNew(['name' => $name]);

        $option->value    = $value;
        $option->autoload = $autoload;
        $option->save();

        $this->cacheValue($name, $value);

        return true;
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

    /**
     * 返回缓存的前缀，可以通过 cachePrefix 属性设置
     * 默认为“类名+_cache:”
     *
     * @return [type] [description]
     */
    public function cachePrefix()
    {
        return property_exists($this, 'cachePrefix') ? $this->cachePrefix : static::class."_cache:";
    }

    /**
     * 把option写入缓存
     * @param  [type]  $name    [description]
     * @param  [type]  $value   [description]
     * @param  integer $minutes [description]
     * @return [type]           [description]
     */
    protected function cacheValue($name, $value, $minutes = 60)
    {
        Cache::put($this->cachePrefix().$name, $value, $minutes);
        $this->autoload[$name] = $value;
    }

    /**
     * Sanitizes a string key.
     * 去掉特殊符号，只允许字母、数组和下横线.
     * @since 3.0.0
     *
     * @param string $key String key
     * @return string Sanitized key
     */
    protected function sanitize_key( $key ) {
        $key = strtolower( $key );
        $key = preg_replace( '/[^a-z0-9_\-]/', '', $key );
        return $key;
    }
}
