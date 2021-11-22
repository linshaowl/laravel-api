<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lswl\Api\Contracts\ResultCodeInterface;
use Lswl\Api\Contracts\VersionModelInterface;
use Lswl\Api\Traits\ResultThrowTrait;
use Lswl\Support\Utils\RequestInfo;

/**
 * 检测版本中间件
 */
class CheckVersionMiddleware
{
    use ResultThrowTrait;

    public function handle(Request $request, Closure $next)
    {
        // 当前版本
        $version = RequestInfo::getParam($request, 'version', 0);

        // 判断版本号是否存在
        if (empty($version)) {
            $this->error(trans('version_no_exist'));
        }

        // 验证版本
        $info = $this->getVersionLastInfo();
        if (!empty($info)) {
            if ($version < $info->code && $info->is_force) {
                $this->error(trans('old_version'), ResultCodeInterface::OLD_VERSION, [
                    'content' => $info->content,
                    'url' => $info->url,
                ]);
            }
        }

        return $next($request);
    }

    /**
     * 获取版本最新信息
     * @return mixed
     */
    protected function getVersionLastInfo()
    {
        return app()->get(VersionModelInterface::class)->getLastInfo();
    }
}
