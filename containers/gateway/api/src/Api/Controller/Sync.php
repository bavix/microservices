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
//        return $this->sync(
//            'store_set',
//            $this->queueData
//                ->data([
//                    'message' => __METHOD__
//                ])
//                ->meta([
//                    'handle_id' => 'H:afbbe472b68e:5'
//                ])
//        );

//        return $this->sync(
//            'store_get',
//            $this->queueData->data([
//                'handle_id' => 'H:afbbe472b68e:5'
//            ])
//        );

        return $this->sync(
            'app_message',
            $this->queueData->data(__METHOD__)
        );
    }

}
