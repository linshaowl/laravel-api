<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Utils;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * 分页辅助
 */
class PaginateHelper
{
    /**
     * 分页处理
     * @param LengthAwarePaginator $paginate
     * @param array|string[] $fields
     * @return array
     */
    public static function paginate(LengthAwarePaginator $paginate, array $fields = []): array
    {
        $res = [
            'current_page' => $paginate->currentPage(),
            'data' => $paginate->getCollection(),
            'first_page_url' => $paginate->url(1),
            'from' => $paginate->firstItem(),
            'last_page' => $paginate->lastPage(),
            'last_page_url' => $paginate->url($paginate->lastPage()),
            'next_page_url' => $paginate->nextPageUrl(),
            'path' => $paginate->path(),
            'page_size' => (int)$paginate->perPage(),
            'prev_page_url' => $paginate->previousPageUrl(),
            'to' => $paginate->lastItem(),
            'total' => $paginate->total(),
        ];

        if ($fields) {
            $res = array_filter(
                $res,
                function ($k) use ($fields) {
                    return in_array($k, $fields);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        return $res;
    }
}
