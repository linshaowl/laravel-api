<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils\Cipher;

/**
 * 令牌加密
 */
class Token extends Base
{
    /**
     * 填充位置
     * @var int
     */
    protected $pos;

    /**
     * 填充长度
     * @var int
     */
    protected $len;

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $this->pos = $this->config['pos'] ?? 5;
        $this->len = $this->config['len'] ?? 6;
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt(string $str): string
    {
        return rawurlencode($this->encryptStr($str));
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt(string $str): string
    {
        return $this->decryptStr(rawurldecode($str));
    }

    /**
     * 验证token
     * @param string $str 需加密字符串
     * @param string $token 加密后的字符串
     * @return boolean
     */
    public function verify(string $str, string $token): bool
    {
        return $str == $this->decryptStr($token);
    }

    /**
     * 加密字符串
     * @param string $str
     * @return string
     */
    private function encryptStr(string $str): string
    {
        $fill = $this->fillStr($this->len);
        $en = openssl_encrypt($str . $fill, $this->method, $this->key, 0, $this->iv);
        $ens = substr($en, 0, $this->pos) . $fill . substr($en, $this->pos);
        return $ens;
    }

    /**
     * 解密字符串
     * @param string $str
     * @return string
     */
    private function decryptStr(string $str): string
    {
        $str = substr($str, 0, $this->pos) . substr($str, $this->pos + $this->len);
        $de = openssl_decrypt($str, $this->method, $this->key, 0, $this->iv);
        $des = substr($de, 0, -$this->len);
        return (string)$des;
    }

    /**
     * 填充字符串
     * @param int $len
     * @return false|string
     */
    private function fillStr(int $len)
    {
        return substr(base64_encode(bin2hex(random_bytes($len))), 0, $len);
    }
}
