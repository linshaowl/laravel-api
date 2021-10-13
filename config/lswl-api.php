<?php

return [
    // 单设备登录临时缓存过期时间（秒）
    'sdl_tmp_expire' => env('LSWL_SDL_TMP_EXPIRE', 10),

    // 最大查询数量
    'max_query_num' => env('LSWL_MAX_QUERY_NUM', 1000),

    // 异常相关
    'exception' => [
        // 异常调试模式
        'debug' => env('LSWL_EXCEPTION_DEBUG', true),
        // 异常文件名称
        'file_name' => [
            'db_exception' => env('LSWL_EXCEPTION_FILE_NAME_DB_EXCEPTION', 'handle.db_exception'),
            'exception' => env('LSWL_EXCEPTION_FILE_NAME_EXCEPTION', 'handle.exception'),
            'error' => env('LSWL_EXCEPTION_FILE_NAME_ERROR', 'handle.error'),
        ],
        // 异常请求消息
        'request_message' => [
            'db_exception' => env('LSWL_EXCEPTION_REQUEST_MESSAGE_DB_EXCEPTION', false),
            'exception' => env('LSWL_EXCEPTION_REQUEST_MESSAGE_EXCEPTION', false),
            'error' => env('LSWL_EXCEPTION_REQUEST_MESSAGE_ERROR', false),
        ],
    ],

    // 运行加密配置
    'runtime' => [
        // 运行调试模式
        'debug' => env('LSWL_RUNTIME_DEBUG', true),
        // 运行加密方法
        'method' => env('LSWL_RUNTIME_METHOD', 'AES-128-CBC'),
        // 运行加密向量
        'iv' => env('LSWL_RUNTIME_IV', ''),
        // 运行加密秘钥
        'key' => env('LSWL_RUNTIME_KEY', ''),
    ],

    // 令牌加密配置
    'token' => [
        // 令牌加密方法
        'method' => env('LSWL_TOKEN_METHOD', 'AES256'),
        // 令牌加密向量
        'iv' => env('LSWL_TOKEN_IV', ''),
        // 令牌加密秘钥
        'key' => env('LSWL_TOKEN_KEY', ''),
        // 令牌填充位置
        'pos' => env('LSWL_TOKEN_POS', 5),
        // 令牌填充长度
        'len' => env('LSWL_TOKEN_LEN', 6),
        // 令牌允许刷新时间（秒） 3天
        'allow_refresh_time' => env('LSWL_TOKEN_ALLOW_REFRESH_TIME', 259200),
        // 令牌提示刷新时间（秒） 2天
        'notice_refresh_time' => env('LSWL_TOKEN_NOTICE_REFRESH_TIME', 172800),
    ],

    // 签名配置
    'signature' => [
        // 是否检测签名
        'check' => env('LSWL_SIGNATURE_CHECK', false),
        // 签名秘钥
        'key' => env('LSWL_SIGNATURE_KEY', ''),
        // 是否检测时间戳
        'check_timestamp' => env('LSWL_SIGNATURE_CHECK_TIMESTAMP', true),
        // 签名时间戳超时（秒）
        'timestamp_timeout' => env('LSWL_SIGNATURE_TIMESTAMP_TIMEOUT', 60),
        // 随机数缓存过期时间（秒）
        'nonce_expire' => env('LSWL_SIGNATURE_NONCE_EXPIRE', 60),
    ],

    // 请求锁定配置
    'request_lock' => [
        // 请求锁定驱动
        'driver' => env('LSWL_REQUEST_LOCK_DRIVER', 'redis'),
        // 请求锁定时间（秒）
        'seconds' => env('LSWL_REQUEST_LOCK_SECONDS', 5),
    ],
];
