```apacheconfig
# 异常调试模式
LSWL_EXCEPTION_DEBUG=true
# 数据库查询异常文件名称
LSWL_EXCEPTION_FILE_NAME_DB_EXCEPTION=handle.db_exception
# 反射、逻辑、运行异常文件名称
LSWL_EXCEPTION_FILE_NAME_EXCEPTION=handle.exception
# 错误文件名称
LSWL_EXCEPTION_FILE_NAME_ERROR=handle.error
# 是否记录数据库查询异常请求消息
LSWL_EXCEPTION_REQUEST_MESSAGE_DB_EXCEPTION=false
# 是否记录反射、逻辑、运行异常请求消息
LSWL_EXCEPTION_REQUEST_MESSAGE_EXCEPTION=false
# 是否记录错误请求消息
LSWL_EXCEPTION_REQUEST_MESSAGE_ERROR=false
```

```apacheconfig
# 运行调试模式,true:不加密
LSWL_RUNTIME_DEBUG=true
# 运行加密方法
LSWL_RUNTIME_METHOD=AES-128-CBC
# 运行加密向量
LSWL_RUNTIME_IV=
# 运行加密秘钥
LSWL_RUNTIME_KEY=
```

```apacheconfig
# 令牌加密方法
LSWL_TOKEN_METHOD=AES256
# 令牌加密向量
LSWL_TOKEN_IV=
# 令牌加密秘钥
LSWL_TOKEN_KEY=
# 令牌填充位置
LSWL_TOKEN_POS=5
# 令牌填充长度
LSWL_TOKEN_LEN=6
# 令牌允许刷新时间（秒） 3天
LSWL_TOKEN_ALLOW_REFRESH_TIME=259200
# 令牌提示刷新时间（秒） 2天
LSWL_TOKEN_NOTICE_REFRESH_TIME=172800
```

```apacheconfig
# 是否检测签名
LSWL_SIGNATURE_CHECK=false
# 签名秘钥
LSWL_SIGNATURE_KEY=
# 是否检测时间戳
LSWL_SIGNATURE_CHECK_TIMESTAMP=true
# 签名时间戳超时（秒）
LSWL_SIGNATURE_TIMESTAMP_TIMEOUT=60
# 签名随机数缓存过期时间（秒）
LSWL_SIGNATURE_NONCE_EXPIRE=60
```

```apacheconfig
# 请求锁定驱动
LSWL_REQUEST_LOCK_DRIVER=redis
# 请求锁定时间（秒）
LSWL_REQUEST_LOCK_SECONDS=5
```

```apacheconfig
# 单设备登录临时缓存过期时间（秒）
LSWL_SDL_TMP_EXPIRE=10
```

```apacheconfig
# 最大查询数量
LSWL_MAX_QUERY_NUM=1000
```
