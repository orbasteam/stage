<?php

namespace Orbas\Stage\Table\Formatter;

/**
 * Show Font-Awesome checked icon
 * 
 */
class FaCheck implements Contract
{
    public function format($value, $params = [])
    {
        return $value ? '<i class="fa fa-check"></i>' : '';
    }

    /**
     * @return bool
     */
    public function htmlEscape()
    {
        return false;
    }
}