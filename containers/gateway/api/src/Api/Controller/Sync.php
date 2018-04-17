<?php

namespace Gateway\Api\Controller;

use Gateway\Api\Controller;

class Sync extends Controller
{

    /**
     * @return array
     */
    protected function default(): array
    {
        $response = $this->client()->doNormal(
            'get_json',
            __METHOD__
        );

        if ($this->client()->returnCode() === GEARMAN_TIMEOUT) {
            throw new \RuntimeException('Unable to retrieve data from Gearman', 400);
        }

        return \json_decode(
            $response,
            true
        );
    }

}
