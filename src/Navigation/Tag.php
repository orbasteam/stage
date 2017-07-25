<?php

namespace Orbas\Stage\Navigation;

class Tag
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $attribute = [];
    
    public function __construct($name, $text, $attribute = [])
    {
        $this->name = $name;
        $this->text = $text;
        $this->attribute = $attribute;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function attributeToString()
    {
        $result = '';
        foreach ($this->attribute as $key => $value) {
            $result .= $key . '="' . $value . '" ';
        }
        
        return $result;
    }
}