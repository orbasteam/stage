<?php
namespace Orbas\Stage;

class Debug
{
    /**
     * @param string $message
     * @param string $label
     */
    public static function log($message, $label = null)
    {
        if (isset(app()->debugbar)) {
            app('debugbar')->addMessage($message, $label);
        }
    }
}