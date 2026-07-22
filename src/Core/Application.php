<?php

declare(strict_types=1);

namespace SPT\Core;

use SPT\Admin\AdminPageManager;
use SPT\Modules\Diagnostics\DiagnosticsModule;
use SPT\Services\AssetManager;
use SPT\Services\SettingsManager;
use SPT\Services\ViewManager;
use SPT\Infrastructure\Http\HttpClient;
use SPT\Infrastructure\GitHub\GitHubClient;
use SPT\Infrastructure\GitHub\GitHubUpdater;
use SPT\Infrastructure\GitHub\WordPressUpdater;
use SPT\Infrastructure\GitHub\GitHubInstaller;
use SPT\Modules\Settings\SettingsModule;
use SPT\Modules\Editor\EditorModule;

final class Application
{
    private static ?self $instance = null;

    private readonly PluginMetadata $metadata;

    private readonly ModuleRegistry $moduleRegistry;

    private readonly ServiceLocator $services;

    private function __construct(string $pluginFile)
    {
        $this->metadata = new PluginMetadata($pluginFile);

        $this->moduleRegistry = new ModuleRegistry();

        $this->services = new ServiceLocator($this);
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
        add_action(
            'plugins_loaded',
            [$this, 'initialize']
        );
    }

    public function initialize(): void
    {
        $this->assets()->register();
        $this->wordPressUpdater()->register();
        $this->installer()->register();
        $this->registerModules();
    }

    private function registerModules(): void
    {
        $this->moduleRegistry
            ->add(...$this->modules())
            ->boot();
    }

    /**
     * @return array<\SPT\Contracts\ModuleInterface>
     */
    private function modules(): array
    {
        return [
            new SettingsModule($this),
            new EditorModule($this),
            new DiagnosticsModule($this),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Metadata
    |--------------------------------------------------------------------------
    */

    public function metadata(): PluginMetadata
    {
        return $this->metadata;
    }

    public function pluginFile(): string
    {
        return $this->metadata->pluginFile();
    }

    public function pluginPath(): string
    {
        return $this->metadata->pluginDirectory();
    }

    public function pluginUrl(): string
    {
        return $this->metadata->pluginUrl();
    }

    public function basename(): string
    {
        return $this->metadata->pluginBasename();
    }

    public function slug(): string
    {
        return $this->metadata->slug();
    }

    public function name(): string
    {
        return $this->metadata->name();
    }

    public function version(): string
    {
        return $this->metadata->version();
    }

    public function textDomain(): string
    {
        return $this->metadata->textDomain();
    }

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    public function assets(): AssetManager
    {
        return $this->services->assets();
    }

    public function settings(): SettingsManager
    {
        return $this->services->settings();
    }

    public function admin(): AdminPageManager
    {
        return $this->services->admin();
    }

    public function views(): ViewManager
    {
        return $this->services->views();
    }

    public function http(): HttpClient
    {
        return $this->services->http();
    }

    public function github(): GitHubClient
    {
        return $this->services->github();
    }

    public function updater(): GitHubUpdater
    {
        return $this->services->updater();
    }

    public function wordPressUpdater(): WordPressUpdater
    {
        return $this->services->wordPressUpdater();
    }

    public function installer(): GitHubInstaller
    {
        return $this->services->installer();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function assetPath(string $asset): string
    {
        return $this->pluginPath() . 'assets/' . ltrim($asset, '/');
    }

    public function assetUrl(string $asset): string
    {
        return $this->pluginUrl() . 'assets/' . ltrim($asset, '/');
    }
}
