## 路径
> `<>` : 必须
>
> `<?>`: 可不填

路径由以下部分组成：

```
<协议>://<域名>/<版本?>/<模块>/<资源>
```

## 请求参数

### API请求参数

> 不使用API请求参数将不会返回 json 格式数据

可使用以下方式：

```php
// header头
X-Requested-With: XMLHttpRequest
// header头
Accept: application/json
```

### 公共请求参数

| 参数      | 描述  |
| --- | --- |
| sort      | 排序字段                                   |
| order     | 排序方式(desc=倒序,asc=顺序)                |
| offset    | 起始位置(limit分页方式,不可和page分页同时使用) |
| limit     | 查询条数(limit分页方式,不可和page分页同时使用) |
| page      | 页数(page分页方式,不可和limit分页同时使用)     |
| page_size | 每页数量(page分页方式,不可和limit分页同时使用) |

### 验证签名请求参数

| 参数      | 描述  |
| --- | --- |
| nonce     | 随机数        |
| timestamp | 请求时间戳(秒) |
| sign      | 签名         |

### 其他请求参数

> 使用 `params` 参数传 `json` 字符串会忽略其他请求参数

可使用 `params`参数传 `json` 字符串:

```
<协议>://<域名>/<版本?>/<模块>/<资源>?params={"contact":"","content":"55","images":"","nonce":"72f1d7d2aba1475eab21d46f77626d52","sign":"1e73cd0526ff1e23f752c54c6c59ea5a","timestamp":"1571819893"}
```

## 请求令牌

可使用以下方式：

```php
// header头: bearer token
Authorization: Bearer 1234

// header头
Token: 1234

// 请求参数
?token=1234
```

## 请求版本

可使用以下方式：

```php
// header头
Version: 1234

// 请求参数
?version=1234
```

## 请求平台

> 平台关键字查看 [PlatformInfo.php](https://github.com/linshaowl/laravel-api/blob/master/src/PlatformInfo.php)

可使用以下方式：

```php
// header头
User-Agent: LswlAndroid/1.0
// header头
Request-Platform: LswlAndroid/1.0

// 请求参数
?request_platform=LswlAndroid%2F1.0
```

## 请求时控制返回结果是否分页

> 默认启用分页

可使用以下方式：

```php
// header头
Is-Enable-Page: 0

// 请求参数,0=不启用,1=启用
?is_enable_page=0
```

## 请求时控制返回结果是否带上分页相关字段

> 默认不启用
>
> 不启用分页时此参数无效

可使用以下方式：

```php
// header头
Is-Result-Page-Field: 0

// 请求参数,0=不启用,1=启用
?is_result_page_field=0
```

启用时 data 字段返回格式：

```js
{
// 当前页码
"current_page": 1,
// 数据
"data": null,
// 每页数量
"page_size": 10,
// 总数
"total": 1
}
```

## 响应刷新后的令牌

> 此时应该更新下本地储存的令牌

```php
// header头
Refresh-Token: 1234
```

## 返回数据格式

统一返回 `200` http状态码，格式如下：

```json
{
    "code": 1,
    "msg": 'success',
    "data": null
}
```

其中 `data` 可能存在的值有以下3种:

```
data:null
data:[]
data:{}
```

## 返回状态码

> 2\* 成功，4\* 失败

| 状态码 | 描述 |
| --- | --- |
| 2000   | 请求成功          |
| 2001   | 请求成功,无数据    |
| 3000   | 接口维护中        |
| 4000   | 请求失败          |
| 4001   | 账号在其他地方登录 |
| 4002   | token不存在      |
| 4003   | token无效        |
| 4004   | token已过期      |
| 4005   | 禁止登录          |
| 4006   | 请求已锁定        |
| 5000   | 发生异常          |
| 5001   | 发生错误          |
| 6000   | 版本过低          |
