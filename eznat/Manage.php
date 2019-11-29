<?php
namespace core;

class Manage
{
    private static $phpPath =  __DIR__."../runenv/linux_php/bin/php";
    public static function start()
    {
        $scriptFile = __DIR__ . "/server.php";
        $php = self::$phpPath;
        exec("{$php} {$scriptFile} start -d", $out);
        return $out;
    }

    public static function restart()
    {
        $scriptFile = __DIR__ . "/server.php";
        $php = self::$phpPath;
        exec("{$php} {$scriptFile} restart -d", $out);
        return $out;
    }

    public static function stop()
    {
        $scriptFile = __DIR__ . "/server.php";
        $php = self::$phpPath;
        exec("{$php} {$scriptFile} stop", $out);
        return $out;
    }

    public static function reload()
    {
        $scriptFile = __DIR__ . "/server.php";
        $php = self::$phpPath;
        exec("{$php}  {$scriptFile} reload", $out);
        return $out;
    }

    public static function status()
    {
        $scriptFile = __DIR__ . "/server.php";
        $php = self::$phpPath;
        exec("{$php}  {$scriptFile} status", $out);
        return $out;
    }
}