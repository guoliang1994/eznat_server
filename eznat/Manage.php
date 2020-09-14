<?php
namespace core;

class Manage
{
    private static $phpPath = 'php';
    public static function do(array $scriptList, $option)
    {
        $out = '';
        foreach ($scriptList as $script) {
            $scriptFile = __DIR__ . "/port_map_server/$script";
            $php = self::$phpPath;
            exec("{$php} {$scriptFile} $option", $out);
        }
        return $out;
    }
    public static function generateScriptFile($port){
        $content = file_get_contents(__DIR__  . '/tpl.php');
        $fileName = "{$port['remote_port']}_{$port['local_port']}.php";
        self::do((array)$fileName, 'stop');
        file_put_contents(__DIR__. '/port_map_server/' . $fileName, $content);
    }
}
