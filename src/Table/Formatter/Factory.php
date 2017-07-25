<?php
namespace Orbas\Stage\Table\Formatter;

use Orbas\Stage\AppException;

/**
 * Create table body formatter
 * 
 */
class Factory
{
    /**
     * @var array
     */
    static protected $formatter = [];

    /**
     * @var array
     */
    static protected $formatterClassNamespace = [
        __NAMESPACE__
    ];

    /**
     * @param string $formatter
     * @param mixed  $value
     * @param array  $arguments
     *
     * @return mixed
     * @throws AppException
     */
    public static function format($formatter, $value, $arguments = [])
    {
        if ($className = self::formatterClassName($formatter)) {
            return self::fromClass($className, $value, $arguments);
        } elseif (is_callable($formatter)) {
            return self::fromFunction($formatter, $value, $arguments);
        }

        throw new AppException('formatter: ' . $formatter . ' not found');
    }

    /**
     * @param string $formatter
     *
     * @return false|string
     * @throws AppException
     */
    protected static function formatterClassName($formatter)
    {
        foreach (self::$formatterClassNamespace as $prefix) {
            
            $className = rtrim($prefix, '\\') . '\\' . studly_case($formatter);
            
            if (class_exists($className)) {
                return $className;
            }
        }

        if (class_exists($formatter)) {
            return $formatter;
        }
        
        return false;
    }

    /**
     * Format your value by function.
     * 
     * @param string $formatter
     * @param mixed  $value
     * @param array  $arguments
     *
     * @return mixed
     */
    protected static function fromFunction($formatter, $value, $arguments)
    {
        array_unshift($arguments, $value);
        return e(call_user_func_array($formatter, $arguments));
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param array $arguments
     *
     * @return string
     */
    protected static function fromClass($name, $value, $arguments)
    {
        $formatter = self::getFormatterClass($name);
        $result    = $formatter->format($value, $arguments);
     
        return $formatter->htmlEscape() ? e($result) : $result;
    }

    /**
     * @param string $name
     *
     * @return Contract
     * @throws AppException
     */
    protected static function getFormatterClass($name)
    {
        if (!isset(self::$formatter[$name])) {
            $formatter = app($name);

            if (!$formatter instanceof Contract) {
                throw new AppException($formatter . ' is NOT instance of ' . Contract::class);
            }
            
            self::$formatter[$name] = $formatter;
        }
        
        return self::$formatter[$name];
    }
}