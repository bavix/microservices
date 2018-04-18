<?php

namespace Gateway\Api;

use Bavix\Helpers\Arr;

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
    public function meta(array $data): self
    {
        $this->workload['meta'] = $data;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return QueueData
     */
    public function metaMarge(array $data): self
    {
        if (empty($this->workload['meta'])) {
            return $this->meta($data);
        }

        $this->workload['meta'] = \array_merge(
            $this->workload['meta'],
            $data
        );

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return $this->workload;
    }

    /**
     * @return string
     */
    public function toJson(): string {
        return \json_encode($this);
    }

}
