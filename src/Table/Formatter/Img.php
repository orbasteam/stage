<?php

namespace Orbas\Stage\Table\Formatter;

/**
 * Show Font-Awesome checked icon
 *
 */
class Img implements Contract
{
    public function format($value, $params = [])
    {
        $width = isset($params[0]) ? 'width="' . $params[0] . '"' : '';
        $height = isset($params[1]) ? 'height="' . $params[1] . '"' : '';

        return "'<img src=\"$value\" $width $height>'";
    }

    /**
     * @return bool
     */
    public function htmlEscape()
    {
        return false;
    }
}