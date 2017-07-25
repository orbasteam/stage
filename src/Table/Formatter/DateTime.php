<?php

namespace Orbas\Stage\Table\Formatter;

use Carbon\Carbon;

class DateTime implements Contract
{
    /**
     * @param mixed $value
     * @param array $params
     *
     * @return string
     */
    public function format($value, $params = [])
    {
        $format = $params[0];

        if ($value instanceof Carbon) {

            if ($method = $this->carbonMethod($value, $format)) {
                return $value->$method();
            }

            return $value->format($format);
        }

        return date($format, strtotime($value));
    }

    /**
     * @return bool
     */
    public function htmlEscape()
    {
        return true;
    }

    /**
     * @param $carbon
     * @param $format
     *
     * @return string|false
     *
     */
    protected function carbonMethod($carbon, $format)
    {
        $method = 'to' . studly_case($format) . 'String';

        if (method_exists($carbon, $method)) {
            return $method;
        }

        return false;
    }
}