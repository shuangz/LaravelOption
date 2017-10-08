<?php

namespace Shuangz\Option;

use Shuangz\Option\Bottle\CacheBottle;
use Shuangz\Option\Bottle\StaticBottle;
use Shuangz\Option\Bottle\EloquentBottle;
use Shuangz\Option\Bottle\BottleInterface;


/**
 * 该类只会实例化一次
 */
class OptionRepository implements BottleInterface
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
        $this->addBottle(new StaticBottle, "static");
        $this->addBottle(new CacheBottle, "cache");
        $this->addBottle(new EloquentBottle, "eloquent");
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
        $name = $this->sanitizeName($name);

        foreach ($this->bottles as $bottle) {
            if(null != $option = $bottle->get($name)) {
                return $option;
            }
        }
        // 所有瓶子都返回null时，最后才返回$default
        return $default;
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
        $name = $this->sanitizeName($name);

        foreach($this->bottles as $bottle) {
            $bottle->update($name, $value);
        }
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
        $name = $this->sanitizeName($name);

        foreach($this->bottles as $bottle) {
            $bottle->add($name, $value);
        }
    }

    /**
     * 删除一个选项
     * @param  字符串 $name 需要删除的选项名称
     * @return 布尔值       返回是否删除成功
     */
    public function delete($name)
    {
        $name = $this->sanitizeName($name);

        foreach($this->bottles as $bottle) {
            $bottle->delete($name);
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
        $this->bottles[$name] = $bottle;
    }
}
