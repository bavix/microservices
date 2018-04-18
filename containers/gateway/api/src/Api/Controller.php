<?php

namespace Gateway\Api;

use Bavix\Router\Route;
use Bavix\Slice\Slice;

abstract class Controller
{

    /**
     * @var \GearmanClient
     */
    private $client;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var Slice
     */
    protected $attributes;

    /**
     * @var QueueData
     */
    protected $queueData;

    /**
     * Controller constructor.
     * @param Route $route
     * @param Slice $attributes
     */
    public function __construct(Route $route, Slice $attributes)
    {
        $this->route = $route;
        $this->attributes = $attributes;
        $this->queueData = new QueueData();
    }

    /**
     * @param $data
     * @return string
     */
    protected function data($data): string {
        if (\is_object($data) || \is_array($data)) {
            return \json_encode($data);
        }

        return (string)$data;
    }

    /**
     * @param string $handleId
     * @return string
     */
    protected function handle(string $handleId): string {
        return \json_encode([
            'handle_id' => $handleId
        ]);
    }

    /**
     * @param string $name
     * @param $data
     * @return string
     */
    protected function sync(string $name, $data): QueueQuery {
        return new QueueQuery(
            $this->client(),
            $this->client()->doNormal($name, $this->data($data))
        );
    }

    /**
     * @param string $name
     * @param $data
     * @return string
     */
    protected function syncLow(string $name, $data): QueueQuery {
        return new QueueQuery(
            $this->client(),
            $this->client()->doLow($name, $this->data($data))
        );
    }

    /**
     * @param string $name
     * @param $data
     * @return string
     */
    protected function syncHigh(string $name, $data): QueueQuery {
        return new QueueQuery(
            $this->client(),
            $this->client()->doHigh($name, $this->data($data))
        );
    }

    /**
     * @param string $name
     * @param $data
     * @return string
     */
    protected function async(string $name, $data): QueueQuery {
        return new QueueQuery(
            $this->client(),
            $this->handle(
                $this->client()->doBackground(
                    $name,
                    $this->data($data)
                )
            )
        );
    }

    /**
     * @param string $name
     * @param $data
     * @return string
     */
    protected function asyncLow(string $name, $data): QueueQuery {
        return new QueueQuery(
            $this->client(),
            $this->handle(
                $this->client()->doLowBackground(
                    $name,
                    $this->data($data)
                )
            )
        );
    }

    /**
     * @param string $name
     * @param $data
     * @return string
     */
    protected function asyncHigh(string $name, $data): QueueQuery {
        return new QueueQuery(
            $this->client(),
            $this->handle(
                $this->client()->doHighBackground(
                    $name,
                    $this->data($data)
                )
            )
        );
    }

    /**
     * @return \GearmanClient
     */
    protected function client()
    {
        if (!$this->client) {
            $this->client = new \GearmanClient();

            $path = \dirname(__DIR__, 2) . '/etc/servers.php';
            $servers = require $path;

            foreach ($servers as $server) {
                $this->client->addServer($server['host'], $server['port']);
            }

            // disable warning
            \error_reporting(E_ALL & ~E_WARNING);

            $timeout = $this->attributes()
                ->getData('timeout', 100);

            $this->client->setTimeout($timeout);
        }

        return $this->client;
    }

    /**
     * @return Slice
     */
    public function attributes(): Slice
    {
        return $this->attributes;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $action = $this->attributes->getRequired('action');
        return $this->$action();
    }

}
