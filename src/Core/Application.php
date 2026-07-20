<?php

declare(strict_types=1);

namespace SPT\Core;

final class Application
{
	private static ?self $instance = null;

	private string $pluginFile;

	private function __construct(string $pluginFile)
	{
		$this->pluginFile = $pluginFile;
	}

	public static function boot(string $pluginFile): self
	{
		if (self::$instance === null) {
			self::$instance = new self($pluginFile);
			self::$instance->register();
		}

		return self::$instance;
	}

	private function register(): void
	{
		add_action('plugins_loaded', [$this, 'pluginsLoaded']);
	}

	public function pluginsLoaded(): void
	{
		Plugin::boot($this);
	}

	public function pluginFile(): string
	{
		return $this->pluginFile;
	}

	public function pluginPath(): string
	{
		return plugin_dir_path($this->pluginFile);
	}

	public function pluginUrl(): string
	{
		return plugin_dir_url($this->pluginFile);
	}
}
