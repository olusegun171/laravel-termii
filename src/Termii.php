<?php

namespace LaravelTermii;

class Termii
{
    /**
     * Method productResolver
     */
    public static function productResolver($product, $arguments): object
    {
        return match ($product) {
            'messaging' => new \LaravelTermii\Products\Messaging(...$arguments),
            'insights' => new \LaravelTermii\Products\Insight,
            'token' => new \LaravelTermii\Products\Token(...$arguments),
        };

    }

    public static function __callStatic($name, $arguments)
    {
        return self::productResolver($name, $arguments);
    }
}
