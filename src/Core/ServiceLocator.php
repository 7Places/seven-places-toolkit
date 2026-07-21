<?php

declare(strict_types=1);

namespace SPT\Core;

use SPT\Admin\AdminPageManager;
use SPT\Services\AssetManager;
use SPT\Services\SettingsManager;
use SPT\Services\ViewManager;
use SPT\Infrastructure\Http\HttpClient;
use SPT\Infrastructure\GitHub\GitHubClient;
use SPT\Infrastructure\GitHub\GitHubUpdater;

final class ServiceLocator
{
    /**
     * Cached service instances.
     *
     * @var array<class-string,object>
     */
    private array $instances = [];

    public function __construct(
        private readonly Application $app,
    ) {
    }

    public function assets(): AssetManager
    {
        /** @var AssetManager */
        return $this->instances[AssetManager::class]
            ??= new AssetManager($this->app);
    }

    public function settings(): SettingsManager
    {
        /** @var SettingsManager */
        return $this->instances[SettingsManager::class]
            ??= new SettingsManager($this->app);
    }

    public function views(): ViewManager
    {
        /** @var ViewManager */
        return $this->instances[ViewManager::class]
            ??= new ViewManager();
    }

    public function admin(): AdminPageManager
    {
        /** @var AdminPageManager */
        return $this->instances[AdminPageManager::class]
            ??= $this->createAdmin();
    }

    private function createAdmin(): AdminPageManager
    {
        $admin = new AdminPageManager();

        $admin->register();

        return $admin;
    }

    public function http(): HttpClient
    {
        /** @var HttpClient */
        return $this->instances[HttpClient::class]
            ??= new HttpClient($this->app);
    }

    public function github(): GitHubClient
    {
        /** @var GitHubClient */
        return $this->instances[GitHubClient::class]
            ??= new GitHubClient($this->app);
    }

    public function updater(): GitHubUpdater
    {
        /** @var GitHubUpdater */
        return $this->instances[GitHubUpdater::class]
            ??= new GitHubUpdater($this->app);
    }

}
