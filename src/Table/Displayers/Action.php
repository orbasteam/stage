<?php

namespace Orbas\Stage\Table\Displayers;

use Illuminate\Database\Eloquent\Model;

class Action implements Displayable
{
    /**
     * @var array
     */
    protected $globalConfig = [];
    
    public function __construct()
    {
        $this->globalConfig = config('stage.table.rowAction');
    }
    
    /**
     * @param string $column
     * @param Model  $item
     * @param array  $config
     *
     * @return string
     */
    public function show($column, Model $item, $config)
    {
        if (empty($config['options']['actions'])) {
            return '';
        }
        
        $html = '';
        
        foreach ($config['options']['actions'] as $action) {
            $html .= $this->button([
                'uri'  => isset($action['uri']) ? $this->parseUri($item, $action['uri']) : '',
                'icon' => $this->icon($action['icon'] ?? ''),
                'text' => $action['text'] ?? '',
                'class' => $this->buttonClass($action['icon'] ?? '')
            ]);
        }
        
        return $html;
    }

    /**
     * @param Model  $item
     * @param string $uri
     *
     * @return mixed|string
     */
    public function parseUri(Model $item, $uri)
    {
        $pattern = '/\{(\w+)\}/';
        preg_match_all($pattern, $uri, $matches);
        
        if (!isset($matches[1])) {
            return '';
        }

        $replacePattern = array_map(function($column) {
            return '/{' . $column .  '}/';
        }, $matches[1]);
        
        $replacement = [];
        foreach ($matches[1] as $column) {
            $replacement[] = $item->$column;
        }
        
        return preg_replace($replacePattern, $replacement, $uri);
    }

    /**
     * @param string $icon
     *
     * @return string
     */
    protected function buttonClass($icon)
    {
        if (isset($this->globalConfig['attributes'][$icon]['class'])) {
            return $this->globalConfig['attributes'][$icon]['class'];
        }
        
        return $this->globalConfig['default']['class'] ?? '';
    }

    /**
     * @param array $config
     *
     * @return string
     */
    protected function button(array $config)
    {
        $text = trim($config['icon'] . ' ' . $config['text']);
        
        return <<<HTML
<a href="{$config['uri']}" class="{$config['class']}">$text</a>
HTML;

    }

    /**
     * @param string $icon
     *
     * @return string
     */
    protected function icon($icon)
    {
        if ($icon) {
            return $icon ? '<i class="fa '. $icon . '"></i>' : '';
        }
        
        return '';
    }
}