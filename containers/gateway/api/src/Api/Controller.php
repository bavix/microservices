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
     * Controller constructor.
     * @param Route $route
     * @param Slice $attributes
     */
    public function __construct(Route $route, Slice $attributes)
    {
        $this->route = $route;
        $this->attributes = $attributes;
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
            \error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
            $this->client->setTimeout(100);
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
