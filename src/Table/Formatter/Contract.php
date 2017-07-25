<?php

namespace Orbas\Stage\Table\Formatter;

interface Contract
{
    /**
     * @param mixed $value
     * @param array $params
     *
     * @return string
     */
    public function format($value, $params = []);

    /**
     * @return bool
     */
    public function htmlEscape();
}