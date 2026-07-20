<?php
declare(strict_types=1);
namespace SevenPlacesToolkit\Core;
class Plugin{
static function boot():void{add_action('plugins_loaded',[self::class,'init']);}
static function init():void{ModuleManager::discover(plugin_dir_path(dirname(__DIR__,2)).'modules');}
}
