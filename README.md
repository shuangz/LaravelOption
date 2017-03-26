#获取扩展包
- 使用`composer require shuangz/laravel-option`命令，将自动下载所需文件，并将依赖写到composer.json文件中。

- 或者编辑`composer.json`文件，添加`shuangz/laravel-option:"master"`到require字段，然后运行`composer update`更新依赖

#安装方法
- 打开config目录下的app.php文件；
- 将`Shuangz\Option\OptionServiceProvider::class`添加到`providers`字段中
- 将`"Option" => Shuangz\Option\OptionFacade::class`添加到`aliases`字段。提示：该步骤是可选的，如果不添加到别名字段，使用是必须使用完整的命名空间。

#使用方法
- 记得`use Option`或者`use Shuangz\Option\OptionFacade as Option`
```Option::get("key") //获取一个设置值
Option::add("key", "value") //增加一个设置
Option::update("key", "value") //更新一个设置```