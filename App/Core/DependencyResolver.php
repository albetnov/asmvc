<?php

namespace Albet\Asmvc\Core;

class DependencyResolver
{

    /**
     * Return a class with dependencies automatically.
     * @param string $class
     * @return mixed
     * @throws Exception
     */
    public function resolve($class)
    {
        $reflector = new \ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("[$class] can't be instantiated.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Return a class back after running a method. (The method will be called with dependencies automatically)
     * @param string $class
     * @param string $method
     * @param mixed $definedParam
     * @return mixed
     * @throws Exception
     */
    public function methodResolver($class, $method, $definedParam = [])
    {
        $class = $this->resolve($class);

        if (!method_exists($class, $method)) {
            throw new \Exception("Method not exist in {$class}.");
        }

        $reflector = new \ReflectionMethod($class, $method);
        $parameter = $reflector->getParameters();
        $dependencies = $this->getDependencies($parameter);
        if ($definedParam !== []) {
            $dependencies[] = $definedParam;
            $class->$method(...$dependencies);
        } else {
            $class->$method(...$dependencies);
        }

        return $class;
    }

    /**
     * Get a dependencies.
     * @param array $parameters
     * @return array
     */
    public function getDependencies($parameters)
    {
        $dependencies = array();
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType();
            if (is_null($dependency)) {
                $nonClass = $this->resolveNonClass($parameter);
                if ($nonClass) {
                    $dependencies[] = $nonClass;
                }
            } else {
                $dependencies[] = $this->resolve($dependency->getName());
            }
        }
        // vdd($debugger);
        return $dependencies;
    }

    /**
     * Return default value if exist.
     * @param ReflectionParameter $parameter
     * @return mixed
     */
    public function resolveNonClass(\ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        } else {
            return "";
        }
    }
}
