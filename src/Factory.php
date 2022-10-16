<?php


namespace Zyan\Sitemap;

/**
 * Class Factory.
 *
 * @package Zyan\Sitemap
 *
 * @author 读心印 <aa24615@qq.com>
 */
class Factory
{
    /**
     * sitemap.
     *
     * @param string $path
     *
     * @return Sitemap
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public static function sitemap($path){
        return new Sitemap($path);
    }

    private function __construct()
    {

    }
}