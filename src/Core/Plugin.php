<?php

declare(strict_types=1);

namespace SPT\Core;

final class Plugin
{
	public static function boot(Application $app): void
	{
		// Future boot sequence:
		//
		// 1. Load configuration
		// 2. Register services
		// 3. Discover modules
		// 4. Register admin pages
		// 5. Load assets
	}
}
