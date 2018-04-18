<?php

static $store = [];
$worker = new GearmanWorker();

$worker->addServer('172.16.140.101', 4730);
$worker->addServer('172.16.140.102', 4730);
$worker->addServer('172.16.140.103', 4730);
$worker->addServer('172.16.140.104', 4730);

$worker->addFunction('store_get', function (GearmanJob $job) use (&$store) {
    $assoc = \json_decode($job->workload(), true);

    if (empty($store[$assoc['data']['handle_id']])) {
        return \json_encode([
            'error' => 'not found `handle id`'
        ]);
    }

    return $store[$assoc['data']['handle_id']];
});

$worker->addFunction('store_set', function (GearmanJob $job) use (&$store) {
    $assoc = \json_decode($job->workload(), true);
    $handleId = $assoc['meta']['handle_id'];
    $store[$handleId] = \json_encode($assoc['data']);
    return \json_encode(['success' => true]);
});

while ($worker->work()) {
    continue;
}
