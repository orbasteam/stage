<?php

namespace Orbas\Stage\Navigation;

class Renderer implements RenderContract
{
    /**
     * @param $item
     * @param $level
     *
     * @return Tag
     */
    public function render($item, $level)
    {
        $attributes = [
            'title' => $item['title']
        ];
        
        $url = $this->parseUrl($item);
        if ($url) {
            $attributes['url'] = $url;
        }
        
        return new Tag($this->getTagName($item, $level), null, $attributes);
    }

    /**
     * @param array   $item
     * @param integer $level
     *
     * @return string
     */
    protected function getTagName($item, $level)
    {
        if ($level == 1) {
            return 'classic-menu';
        } elseif ($level >= 2 && !empty($item['subItems'])) {
            return 'dropdown-submenu';
        }
        
        return 'submenu';
    }
    
    protected function parseUrl($item)
    {
        if (isset($item['url'])) {
            return $item['url'];
        }
        
        if (isset($item['route'])) {
            return route($item['route']);
        }
        
        if (isset($item['action'])) {
            return action($item['action']);
        }
        
        return null;
    }
}