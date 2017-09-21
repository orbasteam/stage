<?php

namespace Orbas\Stage\Table\Displayers;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\AssignOp\Mod;

class Custom implements Displayable
{

    /**
     * @param string $column
     * @param Model  $item
     * @param array  $config
     *
     * @return mixed
     */
    public function show($column, Model $item, $config)
    {
        if (!isset($config['view'])) {
            return '';
        }
        
        return view($config['view'], $this->params($item, $config));
    }

    /**
     * @param Model $item
     * @param array $config
     *
     * @return array
     */
    protected function params(Model $item, $config)
    {
        if (empty($config['params'])) {
            return [];
        }

        $params = [];
        foreach ($config['params'] as $param) {
            if (!isset($param['key']) && isset($param['value'])) {
                continue;
            }
            $params[$param['key']] = $this->parseParams($item, $param['value']);
        }
        
        return $params;
    } 

    /**
     * @param Model  $item
     * @param string $param
     *
     * @return mixed|string
     */
    protected function parseParams(Model $item, $param)
    {
        $pattern = '/\{(\w+)\}/';
        preg_match_all($pattern, $param, $matches);

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

        return preg_replace($replacePattern, $replacement, $param);
    }

}