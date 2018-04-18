<?php

$worker = new GearmanWorker();

$worker->addServer('172.16.140.101', 4730);
$worker->addServer('172.16.140.102', 4730);
$worker->addServer('172.16.140.103', 4730);
$worker->addServer('172.16.140.104', 4730);

$worker->addFunction('app_message', function (GearmanJob $job) {

    $object = \json_decode($job->workload());

    return \json_encode([
        'message' => $object->data ?? null
    ]);
});

while ($worker->work()) {
    continue;
}
