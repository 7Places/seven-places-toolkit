<?php
declare(strict_types=1);
namespace SevenPlacesToolkit\Core;
class Autoloader{
static function register():void{spl_autoload_register([self::class,'load']);}
static function load(string $c):void{
if(str_starts_with($c,'SevenPlacesToolkit\\')){
$r=str_replace('SevenPlacesToolkit\\','',$c);
$f=__DIR__.'/../'.str_replace('\\','/',$r).'.php';
if(file_exists($f)) require $f;
}}}
