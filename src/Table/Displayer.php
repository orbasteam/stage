<?php

namespace Orbas\Stage\Table;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Orbas\Stage\Http\Services\ListService;
use Orbas\Stage\Table\Displayers\Factory;
use Orbas\Stage\Table\Formatter\Factory as Formatter;

class Displayer
{
    const SEPARATOR = '|';

    /**
     * @var $this
     */
    private static $instance;

    /**
     * @return $this
     */
    private static function instance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        
        return self::$instance;
    }

    /**
     *
     * @param string $column
     * @param Model  $entity
     * @param array  $config
     * @param array  $listConfig
     *
     * @return mixed
     */
    public static function output($column, Model $entity, $config = [], $listConfig = [])
    {
        $instance = self::instance();
        $value = Factory::value($column, $entity, $config);
        
        return $instance->format($value, $config);
    }

    /**
     *
     * @param mixed  $value
     * @param array  $config
     *
     * @return mixed
     */
    protected function format($value, $config)
    {
        if (!isset($config['formatter'])) {
            return $value;
        }
        
        list($formatter, $arguments) = $this->parseParams($config['formatter']);

        if ($formatter) {
            return Formatter::format($formatter, $value, $arguments);
        }

        return $value;
    }

    /**
     * parse the given string
     * eg. substr:0,1
     * substr is name, and [0,1] is parameter
     *
     * @param string $string
     *
     * @return array
     */
    protected function parseParams($string)
    {
        list($name, $parameters) = array_pad(explode(':', $string, 2), 2, []);

        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }

        return [$name, $parameters];
    }

    /**
     * @param string $column
     *
     * @return array
     */
    public static function parseColumnName($column)
    {
        if (Str::contains($column, self::SEPARATOR)) {
            return explode(self::SEPARATOR, $column);
        }

        return [$column, ''];
    }

}