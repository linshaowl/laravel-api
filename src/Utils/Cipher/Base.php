<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils\Cipher;

use Lswl\Support\Traits\InstanceTrait;

/**
 * 加密基类
 */
abstract class Base
{
    use InstanceTrait;

    /**
     * 配置信息
     * @var array
     */
    protected $config;

    /**
     * 加密方法
     * @var string
     */
    protected $method;

    /**
     * 加密向量
     * @var string
     */
    protected $iv;

    /**
     * 加密key
     * @var string
     */
    protected $key;

    /**
     * 加密字符串
     * @param string $str
     * @return string
     */
    abstract public function encrypt(string $str): string;

    /**
     * 解密字符串
     * @param string $str
     * @return string
     */
    abstract public function decrypt(string $str): string;

    public function __construct()
    {
        // 初始化
        $this->initialize();
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
        // 场景
        $scene = strtolower(class_basename(get_called_class()));
        // 配置
        $this->config = config(
            sprintf('lswl-api.%s', $scene),
            []
        );

        $this->method = $this->config['method'] ?? '';
        $this->iv = $this->config['iv'] ?? '';
        $this->key = $this->config['key'] ?? '';
    }
}
