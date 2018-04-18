<?php

namespace Gateway\Api;

class QueueData implements \JsonSerializable
{

    /**
     * @var array
     */
    protected $workload;

    /**
     * QueueData constructor.
     */
    public function __construct()
    {
        $this->workload = [
            'data' => null,
            'meta' => null,
        ];
    }

    /**
     * @param $data
     * @return QueueData
     */
    public function data($data): self {
        $this->workload['data'] = $data;
        return $this;
    }

    /**
     * @param $data
     *
     * @return QueueData
     */
    public function meta($data): self
    {
        $this->workload['meta'] = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return $this->workload;
    }

}
