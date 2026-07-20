<?php

declare(strict_types=1);

namespace SPT\Core;

use SPT\Modules\Diagnostics\DiagnosticsModule;

final class Application
{
    private static ?self $instance = null;

    private string $pluginFile;

    /** @var array<string,mixed> */
    private array $pluginData = [];

    private function __construct(string $pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }

    public static function boot(string $pluginFile): self
    {
    if (self::$instance === null) {
        self::$instance = new self($pluginFile);
        self::$instance->registerHooks();
    }

      return self::$instance;
    }

    private function registerHooks(): void
    {
      add_action('plugins_loaded', [$this, 'initialize']);
    }

    private function registerModules(): void
    {
      (new ModuleRegistry())
        ->add(new DiagnosticsModule($this))
        ->boot();
    }

    private function initialize(): void
    {
      $this->loadPluginData();

      $this->registerModules();
    }

    private function loadPluginData(): void
    {
        if (! function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $this->pluginData = get_plugin_data(
            $this->pluginFile,
            false,
            false
        );
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

    public function basename(): string
    {
        return plugin_basename($this->pluginFile);
    }

    public function version(): string
    {
        return $this->pluginData['Version'] ?? '0.0.0';
    }

    public function name(): string
    {
        return $this->pluginData['Name'] ?? '';
    }

    public function textDomain(): string
    {
        return $this->pluginData['TextDomain'] ?? '';
    }

    public function slug(): string
    {
        return dirname($this->basename());
    }

    public function assetPath(string $asset): string
    {
        return $this->pluginPath() . 'assets/' . ltrim($asset, '/');
    }

    public function assetUrl(string $asset): string
    {
        return $this->pluginUrl() . 'assets/' . ltrim($asset, '/');
    }
}
