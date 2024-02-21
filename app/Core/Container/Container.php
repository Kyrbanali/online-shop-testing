<?php

namespace Core\Container;

class Container implements ContainerInterface
{
    private array $services;
    private array $instances;

    public function __construct(array $services = [])
    {
        $this->services = $services;
    }

    public function set(string $class, callable $callback)
    {
        $this->services[$class] = $callback;
    }

    public function get(string $class): object
    {
        //из кэша
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (isset($this->services[$class])) {
            $callback = $this->services[$class];

            $instance = $callback($this);

            //кэшируем объект
            $this->instances[$class] = $instance;

            return $instance;
        }

        $instance = new $class();

        $this->instances[$class] = $instance;

        return new $instance;
    }
}