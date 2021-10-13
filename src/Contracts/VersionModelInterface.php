<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Contracts;

/**
 * 版本模型
 */
interface VersionModelInterface
{
    public const PLATFORM_ANDROID = 1; // 安卓
    public const PLATFORM_APPLE = 2; // 苹果

    public const PLATFORM_TEXTS = [
        self::PLATFORM_ANDROID => '安卓',
        self::PLATFORM_APPLE => '苹果',
    ];

    // 获取最新版本信息
    public function getLastInfo();

    // 通过平台获取最新版本信息
    public function getLastInfoByPlatform(int $platform);
}
