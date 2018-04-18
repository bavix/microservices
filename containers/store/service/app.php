<?php

include_once __DIR__ . '/vendor/autoload.php';

$mongo = new \MongoDB\Client('mongodb://mongodb/');
$worker = new GearmanWorker();
$database = $mongo->store;
$collection = $database->app;

$worker->addServer('172.16.140.101', 4730);
$worker->addServer('172.16.140.102', 4730);
$worker->addServer('172.16.140.103', 4730);
$worker->addServer('172.16.140.104', 4730);

$worker->addFunction('store_get', function (GearmanJob $job) use ($collection) {
    $assoc = \json_decode($job->workload(), true);

    // это пример, так нельзя делать ;)
    if (empty($assoc['data']['handle_id'])) {
        return \json_encode([
            'error' => 'Identifier not found'
        ]);
    }

    $data = $collection->findOne([
        '_id' => $assoc['data']['handle_id']
    ]);

    if (!$data) {
        return \json_encode([
            'error' => 'Data not found'
        ]);
    }

    return $data->data;
});

$worker->addFunction('store_set', function (GearmanJob $job) use ($collection) {
    $assoc = \json_decode($job->workload(), true);
    $handleId = $assoc['meta']['handle_id'];

    /**
     * @var $data \MongoDB\Model\BSONDocument
     */
    $data = $collection->findOne([
        '_id' => $handleId
    ]);

    if (!$data) {
        $collection->insertOne([
            '_id' => $handleId,
            'data' => \json_encode($assoc['data'])
        ]);
    }

    return \json_encode([
        'success' => true,
        'data' => $data->data
    ]);
});

while ($worker->work()) {
    continue;
}
