<?php
namespace Vista\Plantilla;

if(session_status() !=2){
    session_start();
}


class Views
{
    private static $url_raiz = "http://192.168.33.10";
    private static $on = false;
    private static $root = false;



    /**
     * @return string
     */

    public static function getUrlRaiz()
    {
        return self::$url_raiz;
    }

    /**
     * @param string $url_raiz
     */
    public static function setUrlRaiz($url_raiz)
    {
        self::$url_raiz = $url_raiz;
    }

    /**
     * @return boolean
     */
    public static function isOn()
    {
        return self::$on;
    }

    /**
     * @param boolean $on
     */
    public static function setOn($on)
    {
        self::$on = $on;
    }

    /**
     * @return boolean
     */
    public static function isRoot()
    {
        return self::$root;
    }

    /**
     * @param boolean $root
     */
    public static function setRoot($root)
    {
        self::$root = $root;
    }
}
?>