<?php

namespace Orbas\Stage\Table\Displayers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ReflectionClass;

class Factory
{
    const COLUMN    = 0;
    const PRESENTER = 1;
    const ENUM      = 2;
    const ACTION    = 3;

    /**
     * @var array
     */
    protected static $classMap = [];

    /**
     * @var array
     */
    protected static $classInstance = [];

    /**
     * @param string $column
     * @param Model  $item
     * @param array  $config
     *
     * @return mixed
     */
    public static function value($column, Model $item, $config)
    {
        if(!isset($config['config']['type'])) {
        }
        
        return self::getClass($config['config']['type'])->show($column, $item, $config);
    }

    /**
     * @param integer $type
     *
     * @return Displayable
     */
    public static function getClass($type)
    {
        if (empty(self::$classMap)) {
           self::$classMap = self::createClassMap(); 
        }
        
        if (!isset(self::$classInstance[$type])) {
            $class = __NAMESPACE__ . '\\' . self::$classMap[$type];
            self::$classInstance[$type] = new $class();
        }
        
        return self::$classInstance[$type];
    }

    /**
     * create displayer class map
     * @return Collection
     */
    protected static function createClassMap()
    {
        $class = new ReflectionClass(__CLASS__);
        $constants = collect($class->getConstants());
        return $constants->flip()->map(function($name) {
            return ucfirst(strtolower($name));
        });
    }
}