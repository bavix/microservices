<?php

namespace Gateway\Api\Controller;

use Gateway\Api\Controller;
use Gateway\Api\QueueQuery;

class Sync extends Controller
{

    /**
     * @return QueueQuery
     */
    protected function default(): QueueQuery
    {
        return $this->sync(
            'app_message',
            $this->queueData->data(__METHOD__)
        );
    }

}
