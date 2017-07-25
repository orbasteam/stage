<?php

namespace Orbas\Stage\Navigation;

interface RenderContract
{
    /**
     * @param $item
     * @param $level
     *
     * @return \Orbas\Stage\Navigation\Tag
     */
    public function render($item, $level);
}