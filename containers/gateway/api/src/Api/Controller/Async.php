<?php

namespace Gateway\Api\Controller;

use Gateway\Api\Controller;
use Gateway\Api\QueueQuery;

class Async extends Controller
{

    /**
     * @return QueueQuery
     */
    protected function default(): QueueQuery
    {
        return $this->async(
            'app_message',
            $this->queueData
                ->data(__METHOD__)
                ->meta(['target' => 'store_set'])
        );
    }

    /**
     * @return QueueQuery
     */
    protected function get(): QueueQuery
    {
        if (empty($_GET['handle_id'])) {
            throw new \InvalidArgumentException('Handle ID not found');
        }

        return $this->sync('store_get', $this->queueData->data([
            'handle_id' => $_GET['handle_id']
        ]));
    }

}
