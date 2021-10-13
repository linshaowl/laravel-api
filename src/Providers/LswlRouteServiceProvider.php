<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * 路由服务提供者
 */
class LswlRouteServiceProvider extends RouteServiceProvider
{
    /**
     * 排除的文件
     * @var array
     */
    protected $except = [];

    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        parent::boot();
    }

    public function map()
    {
        $files = glob(base_path('routes/*.php'));
        foreach ($files as $file) {
            // 是否排除
            if ($this->isExcept($file)) {
                continue;
            }

            Route::prefix('')->group($file);
        }
    }

    /**
     * 是否排除
     * @param string $file
     * @return bool
     */
    protected function isExcept(string $file): bool
    {
        $name = pathinfo($file, PATHINFO_FILENAME);
        return in_array($name, $this->except) || in_array($name . '.php', $this->except);
    }
}
