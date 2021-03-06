<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Exceptions;

use Exception;

/**
 * 请求结果异常
 */
class ResultException extends Exception
{
    protected $data;
    protected $httpCode;

    public function __construct(int $code, string $msg, $data, int $httpCode)
    {
        $this->setData($data);
        $this->setHttpCode($httpCode);

        parent::__construct($msg, $code, null);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    protected function setData($data)
    {
        $this->data = $data;
    }

    protected function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }
}
