<?php

namespace Orbas\Stage\Table\Formatter;

class Mailto implements Contract
{
    public function format($value, $params = [])
    {
        return '<a href="mailto:' . e($value) . '">' . e($value) . '</a>';
    }

    /**
     * @return bool
     */
    public function htmlEscape()
    {
        return false;
    }
}