<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Handlers;

use Error;
use ErrorException;
use Illuminate\Contracts\Cache\Lock as LockContract;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Lswl\Log\Log;
use Lswl\Api\Contracts\ResultCodeInterface;
use Lswl\Api\Exceptions\ResultException;
use Lswl\Api\Utils\Cipher;
use LogicException;
use ReflectionException;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * 异常处理
 */
class ExceptionHandler extends Handler
{
    protected $code;
    protected $msg;
    protected $data;

    protected $httpCode;

    protected $request;
    protected $headers = [];

    private $key;

    public function report(Throwable $e)
    {
    }

    public function render($request, Throwable $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        // 设置请求对象
        $this->request = $request;

        // 重置属性
        $this->resetProperty();

        // 设置响应数据
        $this->response($e);

        // 响应数据
        $response = [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->data,
        ];
        // 如果是调试模式
        if (config('lswl-api.exception.debug', true)) {
            $response['debug'] = $this->responseDebug($e);
        }
        // 响应处理
        $response = $this->responseHandler($response);

        // 判断刷新的令牌是否存在
        if (!empty($this->request->refreshToken)) {
            // 刷新令牌
            $this->refreshToken($this->request->refreshToken);
            // 单设备登录处理
            $this->sdlHandler($this->request->refreshToken);
        }

        // 解除请求锁定
        $this->unRequestLock();

        // 响应前处理
        $this->responseBeforeHandler();

        // 返回响应数据
        return $this->request->expectsJson()
            ? response()->json($response, $this->httpCode, $this->headers, JSON_UNESCAPED_UNICODE)
            : $this->prepareResponse($request, $e);
    }

    /**
     * 自定义响应
     * @param Throwable $e
     */
    protected function customResponse(Throwable $e)
    {
    }

    /**
     * 返回调试数据
     * @param Throwable $e
     * @return array
     */
    protected function responseDebug(Throwable $e): array
    {
        return [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ];
    }

    /**
     * 刷新令牌
     * @param string $token
     */
    protected function refreshToken(string $token)
    {
        $this->headers['Refresh-Token'] = $token;
    }

    /**
     * 单设备登录处理
     * @param string $token
     */
    protected function sdlHandler(string $token)
    {
    }

    /**
     * 解除请求锁定
     */
    protected function unRequestLock()
    {
        if (
            $this->code != ResultCodeInterface::REQUEST_LOCKED
            && $this->request->requestLock instanceof LockContract
        ) {
            $this->request->requestLock->forceRelease();
        }
    }

    /**
     * 响应前处理
     */
    protected function responseBeforeHandler()
    {
    }

    /**
     * 生成秘钥
     * @return string
     */
    private function buildKey(): string
    {
        return md5(
            sprintf(
                '%s-%s-%s-%s',
                $this->code,
                $this->msg,
                json_encode($this->data),
                $this->httpCode
            )
        );
    }

    /**
     * 验证秘钥
     * @return bool
     */
    private function validateKey(): bool
    {
        return $this->key === $this->buildKey();
    }

    /**
     * 重置属性
     */
    private function resetProperty()
    {
        $this->code = ResultCodeInterface::ERROR;
        $this->msg = trans('error');
        $this->data = null;
        $this->httpCode = ResultCodeInterface::HTTP_ERROR_CODE;
        $this->key = $this->buildKey();
    }

    /**
     * 设置响应数据
     * @param Throwable $e
     */
    private function response(Throwable $e)
    {
        // 自定义响应
        $this->customResponse($e);

        // 秘钥不同,阻止执行
        if (!$this->validateKey()) {
            return;
        }

        if ($e instanceof ResultException) {
            // 返回异常
            $this->code = $e->getCode();
            $this->msg = $e->getMessage();
            $this->data = $e->getData();
            $this->httpCode = $e->getHttpCode();
        } elseif ($e instanceof HttpException) {
            // 系统维护中
            if ($e->getStatusCode() == 503 && $e->getMessage() == 'Service Unavailable') {
                $this->code = ResultCodeInterface::MAINTENANCE;
                $this->msg = trans('maintenance');
                return;
            }

            // 请求异常
            $this->code = ResultCodeInterface::ERROR;
            $this->msg = trans('invalid_request');
        } elseif ($e instanceof QueryException) {
            // 数据库异常
            $this->code = ResultCodeInterface::SYS_EXCEPTION;
            $this->msg = trans('sys_exception');
            Log::name(
                config('lswl-api.exception.file_name.db_exception', 'handle.db_exception')
            )
                ->withDateToName()
                ->withRequestInfo(
                    config('lswl-api.exception.request_message.db_exception', false)
                )
                ->withMessageLineBreak()
                ->throwable($e);
        } elseif ($e instanceof ValidationException) {
            // 验证器异常
            $this->msg = $e->validator->errors()->first();
        } elseif (
            $e instanceof ReflectionException
            || $e instanceof LogicException
            || $e instanceof RuntimeException
            || $e instanceof BindingResolutionException
        ) {
            // 反射、逻辑、运行、绑定解析异常
            $this->code = ResultCodeInterface::SYS_EXCEPTION;
            $this->msg = trans('sys_exception');
            Log::name(
                config('lswl-api.exception.file_name.exception', 'handle.exception')
            )
                ->withDateToName()
                ->withRequestInfo(
                    config('lswl-api.exception.request_message.exception', false)
                )
                ->withMessageLineBreak()
                ->throwable($e);
        } elseif ($e instanceof Error || $e instanceof ErrorException) {
            // 发生错误
            $this->code = ResultCodeInterface::SYS_ERROR;
            $this->msg = trans('sys_error');
            Log::name(
                config('lswl-api.exception.file_name.error', 'handle.error')
            )
                ->withDateToName()
                ->withRequestInfo(
                    config('lswl-api.exception.request_message.error', false)
                )
                ->withMessageLineBreak()
                ->throwable($e);
        }
    }

    /**
     * 响应处理
     * @param array $response
     * @return array|string
     */
    private function responseHandler(array $response)
    {
        try {
            $response = Cipher::response($response);
        } catch (Throwable $e) {
        }

        return $response;
    }
}
