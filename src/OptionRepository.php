<?php

namespace Shuangz\Option;

use Illuminate\Support\Facades\Cache;

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
        $collection  = OptionModel::where('autoload', '=', 1)->lists('value', 'name');

        $this->autoload = $collection->all();
    }


    /**
     * [get description]
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
     * 返回缓存的前缀，可以通过 cachePrefix 属性设置
     * 默认为“类名+_cache:”
     * 
     * @return [type] [description]
     */
    public function cachePrefix()
    {
        return property_exists($this, 'cachePrefix') ? $this->cachePrefix : static::class."_cache:";
    }

    protected function cacheValue($name, $value, $minutes = 60)
    {
        Cache::put($this->cachePrefix().$name, $value, $minutes);
        $this->autoload[$name] = $value;
    }

    /**
     * Sanitizes a string key.
     *
     * 去掉特殊符号，只允许字母、数组和下横线.
     *
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
