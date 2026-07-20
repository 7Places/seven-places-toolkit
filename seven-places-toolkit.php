<?php
declare(strict_types=1);
/**
 * Plugin Name: Seven Places Toolkit
 * Version: 0.1.0
 * Author: Seven Places Productions + Jamon Abercrombie
 */
if(!defined('ABSPATH')) exit;
require __DIR__.'/inc/Core/Autoloader.php';
\SevenPlacesToolkit\Core\Autoloader::register();
\SevenPlacesToolkit\Core\Plugin::boot();
