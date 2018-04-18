<?php

namespace Gateway\Api;

class QueueQuery
{

    /**
     * @var \GearmanClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $response;

    /**
     * SyncQuery constructor.
     *
     * @param \GearmanClient $client
     * @param string $response
     */
    public function __construct(\GearmanClient $client, string $response)
    {
        $this->client = $client;
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function get()
    {
        if ($this->client->returnCode() === GEARMAN_TIMEOUT) {
            throw new \RuntimeException('Unable to retrieve data from Gearman', 400);
        }

        return \json_decode(
            $this->response,
            true
        );
    }

}
