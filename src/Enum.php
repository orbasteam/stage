<?php

namespace Orbas\Stage;

use Illuminate\Support\Collection;
use Orbas\Stage\Enum\Enumable;

class Enum
{
    /**
     * @var array
     */
    protected $enums = [];

    /**
     * @var string
     */
    private $namespace;

    /**
     * Enum constructor.
     *
     * @param string $namespace
     */
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     *
     * @param $name
     *
     * @return Collection
     */
    public function create($name)
    {
        return collect($this->retrieveClass($name)->create());
    }

    /**
     * @param mixed  $key
     *
     * @return mixed
     */
    public function value($key, $name)
    {
        return $this->create($name)->get($key);
    }

    /**
     *
     * @param $name
     *
     * @return Enumable
     */
    protected function retrieveClass($name)
    {
        if (!$this->enumExists($name)) {
            $this->enums[$name] = $this->buildClass($name);
        }
        
        return $this->enums[$name];
    }

    /**
     * Format enum class name
     * 
     * @param string $name
     *
     * @return string
     */

    /**
     * determine if enum class exists
     * 
     * @param string $className
     *
     * @return bool
     */
    protected function enumExists($className)
    {
        return isset($this->enums[$className]);
    }

    /**
     * Build a Enum class
     * 
     * @param string $name
     *
     * @return Enumable
     * @throws AppException
     */
    protected function buildClass($name)
    {
        $className = $this->formatClassName($name);
        
        if (!class_exists($className)) {
            throw new AppException('Enum ' . $className . ' is not exists');
        }

        $enum = new $className();
        if (!$enum instanceof Enumable) {
            throw new AppException('Enum ' . $className . ' is not instance of ' . Enumable::class);
        }
        
        return $enum;
    }

    protected function formatClassName($name)
    {
        return $this->namespace . '\\' . studly_case($name);
    }
}