<?php
namespace Orbas\Stage;

use Orbas\Stage\Navigation\RenderContract;

class Navigation
{
    /**
     * @var string
     */
    protected $defaultConfig = 'stage.navigation';

    /**
     * @var \Closure
     */
    protected static $renderResolver;

    /**
     * @var RenderContract
     */
    protected static $renderer;

    /**
     * @param string $config
     *
     * @return string
     */
    public function render($config = null)
    {
        if (!$config) {
            $config = $this->defaultConfig;
        }
        
        return $this->makeMenu(config($config));
    }

    /**
     * @return RenderContract
     */
    public static function renderer()
    {
        if (!self::$renderer) {
            self::$renderer = call_user_func(self::$renderResolver); 
        }
        
        return self::$renderer;
    }

    /**
     * @param array $navigation
     * @param int   $level
     * @return string
     */
    protected function makeMenu($navigation, $level = 1)
    {
        $string = '';
        foreach ($navigation as $item) {
            
            $tag = self::renderer()->render($item, $level);
            $string .= '<' . $tag->getName() . ' ' . $tag->attributeToString() . '>';
            
            if ($tag->getText()) {
                $string .= $tag->getText();
            }
            
            if (!empty($item['subItems'])) {
                $string .= $this->makeMenu($item['subItems'], ($level + 1));
            }

            $string .= '</' . $tag->getName() . '>';
        }
        
        return $string;
    }

    /**
     * @param \Closure $renderResolver
     */
    public static function rendererResolver(\Closure $renderResolver)
    {
        self::$renderResolver = $renderResolver;
    }
}