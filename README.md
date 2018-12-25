# laravel-custom

laravel 5.5+ 一些自定义的扩展帮助工具

### 安装
首先确保安装好了laravel
```
composer require smallruraldog/laravel-custom
```
然后运行下面的命令来发布资源：
```
php artisan vendor:publish --provider="SmallRuralDog\LaravelCustom\LaravelCustomServiceProvider"
```
生成配置文件
安装完成之后，所有的配置都在`config/laravel-custom.php`文件中。

### 功能列表
- api自定义返回工具
- API 资源扩展

#### api返回工具使用
 `App\Exceptions\Handler`的`render`方法
```php
public function render($request, Exception $exception)
    {
        $report = LaravelCustom::ExceptionReport($exception);
        if ($report) {
            return $report;
        }
        return parent::render($request, $exception);
    }

```

异常拦截自定义
```php
//[message:自定义消息,code:状态码,status:状态]
'do-report' => [
     \Illuminate\Auth\AuthenticationException::class => ['用户未授权', 401, 'no-login']
 ]
 ```