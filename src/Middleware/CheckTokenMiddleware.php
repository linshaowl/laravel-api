<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Lswl\Api\Contracts\ConstAttributeInterface;
use Lswl\Api\Contracts\ResultCodeInterface;
use Lswl\Api\Contracts\UserModelInterface;
use Lswl\Api\Exceptions\ResultException;
use Lswl\Api\Traits\ResultThrowTrait;
use Lswl\Api\Utils\Token;
use Lswl\Support\Utils\Collection;
use Lswl\Support\Utils\Helper;

/**
 * 检测令牌中间件
 */
class CheckTokenMiddleware
{
    use ResultThrowTrait;

    /**
     * 默认场景
     * @var string
     */
    protected $scene = ConstAttributeInterface::DEFAULT_SCENE;

    /**
     * 多场景
     * @var array
     */
    protected $scenes = [];

    public function handle(Request $request, Closure $next, $force = true, ...$scenes)
    {
        // 场景存在
        if (!empty($scenes)) {
            $this->scenes = $scenes;
        }

        try {
            // 用户信息
            $request->userInfo = $this->check($request);
        } catch (ResultException $e) {
            // 需要强制登录
            if (Helper::boolean($force)) {
                $this->error(
                    $e->getMessage(),
                    $e->getCode(),
                    $e->getData(),
                    $e->getHttpCode()
                );
            }

            // 用户信息
            $request->userInfo = new Collection();
        }

        return $next($request);
    }

    /**
     * 获取用户信息
     * @param int $id
     * @return mixed
     */
    protected function getUserInfo(int $id)
    {
        return app()->get(UserModelInterface::class)->getInfoById($id);
    }

    /**
     * 验证
     * @param Request $request
     * @return Builder|Model|object|null
     * @throws ResultException
     */
    private function check(Request $request)
    {
        // token
        $token = Token::get();

        // 判断token是否存在
        if (empty($token)) {
            $this->error(trans('token_no_exist'), ResultCodeInterface::TOKEN_NO_EXISTS);
        }

        // 解析token
        $parse = Token::parse($token);

        // 验证token
        $this->verifyToken($parse);

        // 解析[加密字符, 加密时间, 加密场景]
        [$id, $time] = $parse;

        // 判断token是否有效
        $info = $this->getUserInfo($id);
        if (empty($info)) {
            $this->error(trans('token_invalid'), ResultCodeInterface::TOKEN_INVALID);
        } elseif ($info->is_freeze == ConstAttributeInterface::YES) {
            // 是否冻结
            $this->error(trans('prohibit_login'), ResultCodeInterface::PROHIBIT_LOGIN);
        }

        // 判断是否刷新token
        $noticeTime = config('lswl-api.token.notice_refresh_time', 0);
        if ((time() - $time) >= $noticeTime) {
            // 设置刷新的token
            $request->refreshToken = Token::create($id, $this->scene);
        }

        return $info;
    }

    /**
     * 验证token
     * @param array $parse
     * @throws ResultException
     */
    private function verifyToken(array $parse)
    {
        // 不存在多场景
        if (empty($this->scenes)) {
            $this->scenes = [$this->scene];
        }

        // 默认值
        $verify = [ResultCodeInterface::TOKEN_INVALID, trans('token_invalid')];

        // 多场景验证
        foreach ($this->scenes as $scene) {
            $verify = Token::verify($parse, $scene);
            if ($verify === true) {
                $this->scene = $scene;
                break;
            }
        }

        // 未验证通过
        if ($verify !== true) {
            [$code, $msg] = $verify;
            $this->error($msg, $code);
        }
    }
}
