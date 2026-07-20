<?php
namespace SevenPlacesToolkit\Core;
class ModuleManager{
static function discover(string $b):void{
if(!is_dir($b)) return;
$i=new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($b));
foreach($i as $f){if($f->getFilename()==='Module.php') require_once $f->getPathname();}
}}
