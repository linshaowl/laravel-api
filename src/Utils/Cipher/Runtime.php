<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils\Cipher;

/**
 * 运行加密
 */
class Runtime extends Base
{
    /**
     * {@inheritdoc}
     */
    public function encrypt(string $str): string
    {
        return rawurlencode(
            openssl_encrypt(
                $str,
                $this->method,
                $this->key,
                0,
                $this->iv
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt(string $str): string
    {
        return openssl_decrypt(
            rawurldecode($str),
            $this->method,
            $this->key,
            0,
            $this->iv
        );
    }
}
