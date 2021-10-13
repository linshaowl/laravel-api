<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Illuminate\Http\Request;
use Lswl\Api\Traits\AgentIsTrait;
use Lswl\Support\Traits\InstanceTrait;

/**
 * 请求 Agent 类
 */
class Agent extends \Jenssegers\Agent\Agent
{
    use InstanceTrait;
    use AgentIsTrait;

    /**
     * @var array
     */
    protected $requestPlatforms;

    public function __construct(
        Request $request,
        array $headers = null,
        $userAgent = null
    ) {
        parent::__construct($headers, $userAgent);
        $this->requestPlatforms = RequestPlatform::run($request);
    }
}
