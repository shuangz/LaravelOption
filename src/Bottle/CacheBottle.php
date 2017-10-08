<?php

namespace  Shuangz\Option\Bottle;

use Illuminate\Support\Facades\Cache;

/**
*
*/
class CacheBottle implements BottleInterface
{

    /**
     * 保存到缓存是自动加上的前缀
     *
     * @var string
     */
    protected $prefix = 'CacheBottle：';

    public function get($name)
    {
        return Cache::get($this->peace($name));
    }

    public function update($name, $value)
    {
        Cache::forever($this->peace($name), $value);
    }

    public function add($name, $value)
    {
        if(Cache::has($this->peace($name))){
            return false;
        }
        $this->update($name, $value);
        return true;
    }

    public function delete($name)
    {
        Cache::forget($this->peace($name));
    }

    /**
     * 为了不和其他缓存冲突，我们要对缓存名称做一点处理
     * 这个函数会自动为缓存名字加上前缀
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    protected function peace($name)
    {
    	return $this->prefix.$name;
    }
}