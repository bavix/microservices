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
        $timeout = $this->attributes()
            ->getData('timeout', $this->client()->timeout());

        $this->client()
            ->setTimeout($timeout);

        $response = $this->client()->doNormal(
            'sync',
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
