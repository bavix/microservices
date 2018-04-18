<?php

namespace Gateway\Api\Controller;

use Gateway\Api\Controller;
use Gateway\Api\QueueQuery;

class Async extends Controller
{

    /**
     * @return array
     */
    protected function default(): QueueQuery
    {
        return $this->async(
            'app_message',
            $this->queueData->data(__METHOD__)
        );
    }

    protected function get(): array
    {
        $handleId = $_GET['handle_id'] ?? null;

        if (!$handleId) {
            throw new \InvalidArgumentException('HandleId not found', 400);
        }

        return $this->client()->jobStatus($handleId);
    }

}
