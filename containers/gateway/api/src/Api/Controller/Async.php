<?php

namespace Gateway\Api\Controller;

use Gateway\Api\Controller;

class Async extends Controller
{

    /**
     * @return array
     */
    protected function default(): array
    {
        $handleId = $this->client()->doBackground(
            'get_json',
            __METHOD__
        );

        if ($this->client()->returnCode() === GEARMAN_TIMEOUT) {
            throw new \RuntimeException('Unable to retrieve data from Gearman', 400);
        }

        return [
            'handle_id' => $handleId
        ];
    }

    protected function get(): array
    {
        $handleId = $_GET['handle_id'] ?? null;

        if (!$handleId) {
            throw new \InvalidArgumentException('HandleId not found', 400);
        }

        $stat = $this->client()->jobStatus($handleId);

        var_dump($this->client());die;
    }

}
