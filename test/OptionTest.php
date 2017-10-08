<?php

namespace Tests\Unit;

use DB;
use Cache:
use Option;
use Tests\TestCase;
use App\Model\District;
use Shuangz\Option\OptionModel;
use Shuangz\Option\Bottle\EloquentBottle;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OptionTest extends TestCase
{
    use RefreshDatabase;

    public $testData = [
        'name'  =>"girlFirend",
        'value' => "黄嘉玲"
    ]

    public function setUp()
    {
        parent::setUp();
        Option::update($this->testData['name'], $this->testData['value']);
    }

    /**
     * 测试是否可以正常返回数据
     *
     * @return [type] [description]
     */
    public function testGet()
    {
        $option = Option::get($this->testData['name']);
        $this->assertEquals($option, $this->testData['value']);
    }

    /**
     * 测试是否bottles都写入了数据
     *
     * @return [type] [description]
     */
    public function testInBottles()
    {
        $optionFromDb    = app(EloquentBottle::class)->get($this->testData['name']);
        $optionFromCache = Cache::get('CacheBottle：'.$this->testData['name']);
        $this->assertEquals($optionFromDb->value, $this->testData['value']);
        $this->assertEquals($optionFromCache, $this->testData['value']);
    }

    /**
     * 删除缓存和数据中的数据，只能在静态数组中获取
     *
     * @return [type] [description]
     */
    public function testFromStatic()
    {
        Cache::forget('CacheBottle：'.$this->testData['name']);
        OptionModel::where($this->testData['name'], $this->testData['value'])->delete();
        $option = Option::get($this->testData['name']);
        $this->assertEquals($option, $this->testData['value']);
    }

    /**
     * 测试是否可以在缓存中返回数据
     * 方法直接是在缓存中构造一个数据，然后获取他
     *
     * @return [type] [description]
     */
    public function testFromCache()
    {
        $name  = 'CacheBottle：'."admin";
        $value = "梁双章";
        Cache::add($name, $value);
        $option = Option::get($name);
        $this->assertEquals($option, $value);
    }

    public function testFromDb()
    {
        $name  = "admin";
        $value = "梁双章";
        app(EloquentBottle::class)->set($this->testData['name']);
        $option = Option::get($name);
        $this->assertEquals($option, $value);
    }


}
