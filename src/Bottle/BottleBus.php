<?php

namespace  Shuangz\Option\Bottle;

use Exception;

class BottleBus implements BottleInterface
{
    /**
     * 已经加载的容器瓶子
     *
     * @var [type]
     */
    protected $bottles = [];

    public function __construct()
    {
        //这里实例化bottle会造成强耦合
        //就当他们是默认的bottle吧
        //前面添加的要比后面添加的权重高
        //也是就优先从该bottle中取值
        $this->addBottle(new StaticBottle);
        $this->addBottle(new CacheBottle);
        $this->addBottle(new EloquentBottle);
    }

    public function get($name)
    {
        $name = $this->sanitizeName($name);

        foreach ($bottles as $bottle) {
            if(null != $option = $bottle->get($name)) {
                return $option;
        }
        // 所有瓶子都返回null时，最后才返回null
        return null;
    }

    public function set($name, $value)
    {
        $name = $this->sanitizeName($name);

        foreach ($bottles as $bottle) {
            $bottle->set($name, $value);
        }
    }

    /**
     * 全部装换成小写字母
     * 去掉特殊符号，只允许字母、数组和下横线.
     *
     * @param  string  $name  String key
     * @return string         Sanitized key
     */
    protected function sanitizeName( $name ) {
        $name = strtolower( $name );
        $name = preg_replace( '/[^a-z0-9_\-]/', '', $name );
        if ($name) {
            return $name;
        }
        throw new Exception("Name is illegal");
    }

    public function addBottle(BottleInterface $bottle, $name = '')
    {
        $this->addBottle[$name] = $bottle;
    }
}