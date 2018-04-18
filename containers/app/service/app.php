<?php

$worker = new GearmanWorker();
$client = new GearmanClient();

$worker->addServer('172.16.140.101', 4730);
$client->addServer('172.16.140.101', 4730);

$worker->addServer('172.16.140.102', 4730);
$client->addServer('172.16.140.102', 4730);

$worker->addServer('172.16.140.103', 4730);
$client->addServer('172.16.140.103', 4730);

$worker->addServer('172.16.140.104', 4730);
$client->addServer('172.16.140.104', 4730);

$worker->addFunction('app_message', function (GearmanJob $job) use ($client) {
    $object = \json_decode($job->workload(), true);

    $result = [
        'message' => $object['data'] ?? null
    ];

    if ($object['meta']['type'] === 'async') {
        $client->doNormal($object['meta']['target'], \json_encode([
            'data' => $result,
            'meta' => [
                'handle_id' => $job->handle(),
                'type' => 'sync'
            ]
        ]));
    }

    return \json_encode($result);
});

while ($worker->work()) {
    continue;
}
