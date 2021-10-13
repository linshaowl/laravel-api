## 目录

- [安装配置](#%E5%AE%89%E8%A3%85%E9%85%8D%E7%BD%AE)
- [使用说明](#%E4%BD%BF%E7%94%A8%E8%AF%B4%E6%98%8E)
	- [快速使用](#%E5%BF%AB%E9%80%9F%E4%BD%BF%E7%94%A8)
		- [中间件](#%E4%B8%AD%E9%97%B4%E4%BB%B6)
		- [异常处理](#%E5%BC%82%E5%B8%B8%E5%A4%84%E7%90%86)
		- [使用路由服务提供者](#%E4%BD%BF%E7%94%A8%E8%B7%AF%E7%94%B1%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
		- [控制器](#%E6%8E%A7%E5%88%B6%E5%99%A8)
		- [模型](#%E6%A8%A1%E5%9E%8B)
		- [服务层](#%E6%9C%8D%E5%8A%A1%E5%B1%82)
        - [数据访问层](#%E6%95%B0%E6%8D%AE%E8%AE%BF%E9%97%AE%E5%B1%82)
	- [控制器](#%E6%8E%A7%E5%88%B6%E5%99%A8-1)
	- [模型](#%E6%A8%A1%E5%9E%8B-1)
	    - [普通模型](#%E6%99%AE%E9%80%9A%E6%A8%A1%E5%9E%8B)
	    - [中间表模型](#%E4%B8%AD%E9%97%B4%E8%A1%A8%E6%A8%A1%E5%9E%8B)
	- [服务层](#%E6%9C%8D%E5%8A%A1%E5%B1%82-1)
    - [数据访问层](#%E6%95%B0%E6%8D%AE%E8%AE%BF%E9%97%AE%E5%B1%82-1)
	- [中间件](#%E4%B8%AD%E9%97%B4%E4%BB%B6-1)
	- [异常处理](#%E5%BC%82%E5%B8%B8%E5%A4%84%E7%90%86-1)
	- [服务提供者](#%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
	    - [API服务提供者](#api%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
	    - [契约服务提供者](#%E5%A5%91%E7%BA%A6%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
	    - [路由服务提供者](#%E8%B7%AF%E7%94%B1%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
	- [trait介绍](#trait%E4%BB%8B%E7%BB%8D)
		- [RequestInfoTrait.php](#requestinfotraitphp)
		- [ResourceControllerTrait.php](#resourcecontrollertraitphp)
		- [ResourceServiceTrait.php](#resourceservicetraitphp)
	    - [ResultThrowTrait.php](#resultthrowtraitphp)
		- [UserInfoTrait.php](#userinfotraitphp)
	- [工具类介绍](#%E5%B7%A5%E5%85%B7%E7%B1%BB%E4%BB%8B%E7%BB%8D)
	    - [Agent.php](#agentphp)
	    - [SdlCache.php](#sdlcachephp)
	    - [Token.php](#tokenphp)

## 安装配置

使用以下命令安装：
```
composer require lswl/laravel-api
```
发布文件[可选]：
```php
// 发布所有文件
php artisan vendor:publish --tag=lswl-api

// 只发布配置文件
php artisan vendor:publish --tag=lswl-api-config

// 只发布迁移文件
php artisan vendor:publish --tag=lswl-api-migrations
```

## 使用说明

> 环境变量值参考：[env](docs/ENV.md)
>
> restful参考: [restful](docs/RESTFUL.md)

### 快速使用

1. [安装](#%E5%AE%89%E8%A3%85%E9%85%8D%E7%BD%AE)
2. [发布配置[可选]](#%E5%AE%89%E8%A3%85%E9%85%8D%E7%BD%AE)
3. [注册中间件](#%E4%B8%AD%E9%97%B4%E4%BB%B6)
4. [继承异常处理程序](#%E5%BC%82%E5%B8%B8%E5%A4%84%E7%90%86)
5. [使用路由服务提供者](#%E4%BD%BF%E7%94%A8%E8%B7%AF%E7%94%B1%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
6. [示例代码](#%E7%A4%BA%E4%BE%8B%E4%BB%A3%E7%A0%81)

#### 中间件
- 必须注册全局中间件 `Lswl\Api\Middleware\ParamsHandlerMiddleware`
- 可选中间件查看 [中间件列表](#%E4%B8%AD%E9%97%B4%E4%BB%B6-1)

#### 异常处理

- 修改 `App\Exceptions\Handler` 继承的方法为  `Lswl\Api\Handlers\ExceptionHandler`
- 其他异常捕获重写父类 `customResponse()`  方法，内容参考 `response`

#### 使用路由服务提供者

- 修改 `App\Providers\RouteServiceProvider` 继承的方法为  `Lswl\Api\Providers\LswlRouteServiceProvider`
- 路由服务提供者会自动扫描 `routes` 目录生成路由

#### 示例代码
```php
// route
Route::get('tests', 'TestController@index');

// controller
use Lswl\Api\Controllers\BaseController;
use \Lswl\Api\Traits\ResourceControllerTrait;

class TestController extends BaseController
{
    use ResourceControllerTrait;
}

// service
use \Lswl\Api\Services\BaseService;
class TestService extends BaseService
{
    public function index()
    {
        $this->success('this is tests');
    }
}
```

#### 控制器

- 直接继承 `Lswl\Api\Controllers\BaseController`

#### 模型

- 可选继承 `Lswl\Api\Models\BaseModel` 、 `Lswl\Api\Models\BasePivot` 、 `Lswl\Api\Models\UserModel`  、`Lswl\Api\Models\VersionModel`

#### 服务层

- 直接继承 `Lswl\Api\Services\BaseService`

#### 数据访问层

- 直接继承 `Lswl\Api\Daos\BaseDao`

### 控制器

> 需继承 `Lswl\Api\Controllers\BaseController`

- 可使用 `Lswl\Api\Traits\RequestInfoTrait` 里的参数
- 可使用 `Lswl\Api\Traits\UserInfoTrait` 里的参数、方法

### 模型

#### 普通模型

> 需继承 `Lswl\Api\Models\BaseModel`

- 可使用 `Lswl\Database\Traits\DatabaseTrait` 里的方法

#### 中间表模型

> 需继承 `Lswl\Api\Models\BasePivot`

- 可使用 `Lswl\Database\Traits\DatabaseTrait` 里的方法

### 服务层

> 需继承 `Lswl\Api\Services\BaseService`

- 可使用 `Lswl\Api\Traits\RequestInfoTrait` 里的参数
- 可使用 `Lswl\Api\Traits\UserInfoTrait` 里的参数、方法

### 数据访问层

> 需继承 `Lswl\Api\Daos\BaseDao`

- 可使用 `Lswl\Api\Traits\DaoSelectTrait` 方法

### 中间件

> 用法加粗为必须调用

|   中间件   |   别名   |   用法   |   需要实现的契约或继承模型   |
| ---- | ---- | ---- | ---- |
| `Lswl\Api\Middleware\ParamsHandlerMiddleware`  | `lswl.params.handler` | **参数处理** | --- |
| `Lswl\Api\Middleware\ConvertEmptyStringsToNullMiddleware` | `lswl.convert.empty.strings.to.null` | 转换空字符串为null | --- |
| `Lswl\Api\Middleware\TrimStringsMiddleware` | `lswl.trim.strings` | 清除字符串空格 | --- |
| `Lswl\Api\Middleware\RequestLockMiddleware` | `lswl.request.lock` | 请求锁定 | --- |
| `Lswl\Api\Middleware\RequestLogMiddleware` | `lswl.request.log` | 记录请求日志(debug) | --- |
| `Lswl\Api\Middleware\CheckVersionMiddleware` | `lswl.check.version` | 检测应用版本 | `Lswl\Api\Contracts\VersionModelInterface`<br />`Lswl\Api\Models\VersionModel` |
| `Lswl\Api\Middleware\CheckSignatureMiddleware` | `lswl.check.signature` | 验证请求签名 | --- |
| `Lswl\Api\Middleware\CheckTokenMiddleware` | `lswl.check.token` | 检测token，设置用户数据 | `Lswl\Api\Contracts\UserModelInterface`<br />`Lswl\Api\Models\UserModel` |
| `Lswl\Api\Middleware\CheckSdlMiddleware` | `lswl.check.sdl` | 单设备登录，需要复写 `Lswl\Api\Handlers\ExceptionHandler->sdlHandler()` | --- |

### 异常处理

> `App\Exceptions\Handler` 继承 `Lswl\Api\Handlers\ExceptionHandler`
>
> 其他异常捕获调用父类 `response()`  方法并重写，参考 `Lswl\Api\Handlers\ExceptionHandler->response()`

### 服务提供者

#### API服务提供者

>`Lswl\Api\Providers\LswlApiServiceProvider`

- 注册路由中间件
- 注册命令
- 合并配置
- 发布文件

#### 契约服务提供者

> `Lswl\Api\Providers\LswlContractServiceProvider`

- 绑定契约 `Lswl\Api\Contracts\RequestParamsInterface` 实现
- 绑定契约 `Lswl\Api\Contracts\AgentInterface` 实现
- 绑定契约 `Lswl\Api\Contracts\UserModelInterface` 实现
- 绑定契约 `Lswl\Api\Contracts\VersionModelInterface` 实现

#### 路由服务提供者

> `Lswl\Api\Providers\LswlRouteServiceProvider`
>
> 默认不启用

- 注册 `base_path('routes')` 下面所有 php 文件到路由

### trait介绍

#### RequestInfoTrait.php

> `Lswl\Api\Traits\RequestInfoTrait`
>
> 请求信息绑定

使用类:

- `Lswl\Api\Controllers\BaseController`
- `Lswl\Api\Services\BaseService`

#### ResourceControllerTrait.php

> `Lswl\Api\Traits\ResourceControllerTrait`
>
> 资源控制器 trait

#### ResourceServiceTrait.php

> `Lswl\Api\Traits\ResourceServiceTrait`
>
> 资源服务 trait

#### ResultThrowTrait.php

> `Lswl\Api\Traits\ResultThrowTrait`
>
> 异常抛出辅助

#### UserInfoTrait.php

> `Lswl\Api\Traits\UserInfoTrait`
>
> 用户信息绑定

使用类:

- `Lswl\Api\Controllers\BaseController`
- `Lswl\Api\Services\BaseService`

### 工具类介绍

#### Agent.php

> `Lswl\Api\Utils\Agent`
>
> 请求 Agent 类

#### SdlCache.php

> `Lswl\Api\Utils\SdlCache`
>
> 单设备登录类

#### Token.php

> `Lswl\Api\Utils\Token`
>
> 令牌相关类
