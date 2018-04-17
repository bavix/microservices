<?php

$worker = new GearmanWorker();

$worker->addServer('172.16.140.101', 4730);
$worker->addServer('172.16.140.102', 4730);
$worker->addServer('172.16.140.103', 4730);
$worker->addServer('172.16.140.104', 4730);

$worker->addFunction('sync', function (GearmanJob $job) {
    return \json_encode([
        'message' => $job->workload()
    ]);
});

while ($worker->work()) {
    continue;
}
