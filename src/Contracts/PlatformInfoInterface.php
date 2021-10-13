<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Contracts;

/**
 * 平台信息
 */
interface PlatformInfoInterface
{
    // 所在平台
    public const OTHER = 'other';
    public const PC = 'pc';
    public const MOBILE = 'mobile';
    public const ANDROID = 'android';
    public const IOS = 'ios';
    public const WECHAT = 'wechat';
    public const WECHAT_MP = 'wechat_mp';
    public const WORK_WECHAT = 'work_wechat';
    public const WORK_WECHAT_MP = 'work_wechat_mp';
    public const ALIPAY = 'alipay';
    public const ALIPAY_MP = 'alipay_mp';

    /**
     * 平台信息 [关键字 => 平台]
     * @var array
     */
    public const KEYWORDS_PLATFORMS = [
        'LswlPc' => self::PC,
        'LswlMobile' => self::MOBILE,
        'LswlAndroid' => self::ANDROID,
        'LswlIos' => self::IOS,
        'LswlWechat' => self::WECHAT,
        'LswlWechatMp' => self::WECHAT_MP,
        'LswlWorkWechat' => self::WORK_WECHAT,
        'LswlWorkWechatMp' => self::WORK_WECHAT_MP,
        'LswlAlipay' => self::ALIPAY,
        'LswlAlipayMp' => self::ALIPAY_MP,
    ];
}
