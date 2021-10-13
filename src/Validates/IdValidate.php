<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Api\Validates;

class IdValidate extends BaseValidate
{
    public function rules(): array
    {
        return [
            'id' => 'bail|required|integer|gt:0',
        ];
    }

    /**
     * 检测包含0的id
     * @param array $data
     * @return array
     */
    public function checkZero(array $data): array
    {
        $rules['id'] = 'bail|required|integer';
        return $this->validate($data, $rules, $this->messages(), $this->attributes());
    }
}
