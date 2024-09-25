<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Container;
use Closure;
use Exception;
use Lanser\MyFreamwork\Core\Container\Exception\ServiceNotFoundException;
use ReflectionClass;

abstract class Container
{
    // Storage for bindings
    private array $bindings = [];

    // Storage for shared (singleton) instances
    private array $instances = [];

    public function __construct()
    {
    }

    /**
     * Bind an interface or class to a concrete implementation.
     *
     * @param string $abstract The name of the interface or class
     * @param callable|string|null $concrete The concrete implementation or factory
     * @param bool $shared If true, the instance will be shared (singleton)
     */
    public function bind(string $abstract, callable|string $concrete = null, bool $shared = false): void
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }




    /**
     * Register a shared (singleton) binding.
     *
     * @param string $abstract
     * @param callable|string|null $concrete
     */
    public function singleton(string $abstract, callable|string $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Resolve the given abstract type to a concrete instance.
     *
     * @param string $abstract
     * @return mixed
     * @throws Exception
     */
    public function make(string $abstract): mixed
    {
        // Check if the instance is already shared
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // Check if the binding exists
        if (!isset($this->bindings[$abstract])) {
            return $this->build($abstract);
        }

        $concrete = $this->bindings[$abstract]['concrete'];
        $object = $this->build($concrete);

        // If the binding is shared, store the instance
        if ($this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * Build an instance of the given concrete class.
     *
     * @param string|Closure $concrete
     * @return mixed
     * @throws ServiceNotFoundException
     * @throws \ReflectionException
     * @throws Exception
     */
    protected function build(string|Closure $concrete): mixed
    {
        // If the concrete is a callable, call it and return the result
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        // Use reflection to resolve dependencies
        $reflector = new ReflectionClass($concrete);

        // Check if the class is instantiable
        if (!$reflector->isInstantiable()) {
            throw new ServiceNotFoundException("Cannot instantiate $concrete.");
        }

        $constructor = $reflector->getConstructor();

        // If there is no constructor, just create the instance
        if (is_null($constructor)) {
            return new $concrete;
        }

        // Resolve constructor parameters
        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType();

            if ($dependency && !$dependency->isBuiltin()) {
                $dependencies[] = $this->make($dependency->getName());
            } else {
                throw new Exception("Cannot resolve parameter '{$parameter->getName()}'.");
            }
        }

        // Create the class instance with resolved dependencies
        return $reflector->newInstanceArgs($dependencies);
    }


    abstract public function register(): void;
}