<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Traits;

use Lswl\Api\Contracts\PlatformInfoInterface;
use Lswl\Api\Utils\RequestPlatform;

/**
 * Agent 判断辅助
 */
trait AgentIsTrait
{
    /**
     * 是否桌面端
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isDesktop($userAgent = null, $httpHeaders = null): bool
    {
        return parent::isDesktop($userAgent, $httpHeaders)
            || $this->requestPlatformIs(
                PlatformInfoInterface::PC,
                $userAgent,
                $httpHeaders
            );
    }

    /**
     * 是否移动端
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isMobile($userAgent = null, $httpHeaders = null): bool
    {
        return parent::isMobile($userAgent, $httpHeaders)
            || $this->requestPlatformIs(
                PlatformInfoInterface::MOBILE,
                $userAgent,
                $httpHeaders
            );
    }

    /**
     * 是否苹果手机
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isIPhone($userAgent = null, $httpHeaders = null): bool
    {
        return $this->is('IPhone', $userAgent, $httpHeaders)
            || $this->requestPlatformIs(
                PlatformInfoInterface::IOS,
                $userAgent,
                $httpHeaders
            );
    }

    /**
     * 是否苹果系统
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isIOS($userAgent = null, $httpHeaders = null): bool
    {
        return $this->is('IOS', $userAgent, $httpHeaders)
            || $this->requestPlatformIs(
                PlatformInfoInterface::IOS,
                $userAgent,
                $httpHeaders
            );
    }

    /**
     * 是否 Safari 浏览器
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isSafari($userAgent = null, $httpHeaders = null): bool
    {
        return $this->is('Safari', $userAgent, $httpHeaders)
            || $this->requestPlatformIs(
                PlatformInfoInterface::IOS,
                $userAgent,
                $httpHeaders
            );
    }

    /**
     * 是否安卓
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isAndroid($userAgent = null, $httpHeaders = null): bool
    {
        return $this->is('Android', $userAgent, $httpHeaders)
            || $this->requestPlatformIs(
                PlatformInfoInterface::ANDROID,
                $userAgent,
                $httpHeaders
            );
    }

    /**
     * 是否安卓系统
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isAndroidOS($userAgent = null, $httpHeaders = null): bool
    {
        return $this->is('AndroidOS', $userAgent, $httpHeaders)
            || $this->requestPlatformIs(
                PlatformInfoInterface::ANDROID,
                $userAgent,
                $httpHeaders
            );
    }

    /**
     * 是否微信
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isWechat($userAgent = null, $httpHeaders = null): bool
    {
        return $this->is('Wechat', $userAgent, $httpHeaders)
            || $this->requestPlatformIs(
                PlatformInfoInterface::WECHAT,
                $userAgent,
                $httpHeaders
            );
    }

    /**
     * 是否微信小程序
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isWechatMp($userAgent = null, $httpHeaders = null): bool
    {
        return $this->requestPlatformIs(PlatformInfoInterface::WECHAT_MP, $userAgent, $httpHeaders);
    }

    /**
     * 是否企业微信
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isWorkWechat($userAgent = null, $httpHeaders = null): bool
    {
        return $this->requestPlatformIs(PlatformInfoInterface::WORK_WECHAT, $userAgent, $httpHeaders);
    }

    /**
     * 是否企业微信小程序
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isWorkWechatMp($userAgent = null, $httpHeaders = null): bool
    {
        return $this->requestPlatformIs(PlatformInfoInterface::WORK_WECHAT_MP, $userAgent, $httpHeaders);
    }

    /**
     * 是否支付宝
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isAlipay($userAgent = null, $httpHeaders = null): bool
    {
        return $this->requestPlatformIs(PlatformInfoInterface::ALIPAY, $userAgent, $httpHeaders);
    }

    /**
     * 是否支付宝小程序
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isAlipayMp($userAgent = null, $httpHeaders = null): bool
    {
        return $this->requestPlatformIs(PlatformInfoInterface::ALIPAY_MP, $userAgent, $httpHeaders);
    }

    /**
     * 请求平台判断
     * @param string $platform
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function requestPlatformIs(string $platform, $userAgent = null, $httpHeaders = null): bool
    {
        if ($userAgent) {
            $this->requestPlatforms = RequestPlatform::check($userAgent);
        }

        return in_array($platform, $this->requestPlatforms);
    }
}
