<?php

namespace Gateway\Api;

use Bavix\Helpers\Str;
use Bavix\Router\Route;
use Bavix\Router\Router;
use Bavix\Slice\Slice;

class App 
{

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var Router
     */
    protected $router;

    /**
     * App constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return Route
     */
    protected function route(): Route
    {
        if (!$this->route) {
            $this->route = $this->router->getCurrentRoute();
        }
        
        return $this->route;
    }

    /**
     * @return Controller
     */
    protected function init(): Controller
    {
        $namespace = Controller::class;
        $attributes = new Slice($this->route()->getAttributes());
        $processor = $attributes->getRequired('processor');
        $processor = Str::low($processor);
        $class = $namespace . '\\' . Str::ucFirst($processor);

        if (!\class_exists($class)) {
            throw new \InvalidArgumentException(
                \sprintf('Processor `%s` not found', $processor),
                400
            );
        }
        
        return new $class($this->route(), $attributes);
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        $object = $this->init();

        return new Response($object->execute());
    }

}
