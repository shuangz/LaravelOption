<?php

namespace Shuangz\Option;

use Illuminate\Support\Facades\Cache;

class OptionRepository{

    use OptionFormater;

    protected $autoload = array();
    

    public function __construct()
    {
        $this->loadAutoOptions();
    }

    public function loadAutoOptions()
    {
        $autoload = OptionModel::where('autoload', '=', 1)->lists('value', 'name'); 

        foreach ($autoload as $name => $value)
        {
            $this->autoload[$name] = $this->maybe_unserialize($value);
        }
    }

    public function cachePrefix()
    {
        return property_exists($this, 'cachePrefix') ? $this->cachePrefix : static::class."_cache:";
    }

    public function rememberForCached($key, $minutes, Closure $callback)
    {
        Cache::remember($this->cachePrefix().$key, $minutes, $callback);
    }

    public function putToCached($key, $value, $minutes = null)
    {
        Cache::put($this->cachePrefix().$key, $value, $minutes = null);
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

        if ( empty( $name ) )
        {
            return false;
        }

        if (isset($this->autoload[$name]))
        {
            return $this->autoload[$name];
        }
        else
        {
            $value = $this->rememberForCached($name, 60, function() use ($name) {
                            OptionModel::where('name', $name)->pluck('value');
                        });

            if ($value == null)
            {
                return $default;
            }

            $value                 = $this->maybe_unserialize($value);
            $this->autoload[$name] = $value;
            return $value;
        }
    }

    public function update($name, $value)
    {
        $name  = $this->sanitize_key($name);
        $value = $this->maybe_serialize( $value );

        if ($this->get($name) !== null) {

            OptionModel::where('name', $name)->update(['value' => $value]);

            $this->putToCached($name, $value, 60);

        } else {

            OptionModel::insert(['name' => $name, 'value' => $value]);

            $this->putToCached($name, $value, 60);
        }

        $this->autoload[$name] = $value;
        return true;
    }

    public function add($name, $value)
    {
        $name  = $this->sanitize_key($name);
        $value = $this->maybe_serialize( $value );

        if ($this->get($name) !== null)
        {
            return false;
        }
        else
        {
            OptionModel::insert(['name' => $name, 'value' => $value]);

            $this->putToCached($name, $value, 60);

        }

        $this->autoload[$name] = $value;
        return true;
    }


}
