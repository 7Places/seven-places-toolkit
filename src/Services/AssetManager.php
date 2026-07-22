<?php

declare(strict_types=1);

namespace SPT\Services;

use SPT\Core\Application;

final class AssetManager
{
    public function __construct(
        private readonly Application $app,
    ) {
    }

    /**
     * Get the URL to an asset.
     */
    public function url(string $file): string
    {
        return $this->app->assetUrl($file);
    }

    /**
     * Get the filesystem path to an asset.
     */
    public function path(string $file): string
    {
        return $this->app->assetPath($file);
    }

    /**
     * Get the plugin version used for cache busting.
     */
    public function version(): string
    {
        return $this->app->version();
    }

    /**
     * Enqueue a stylesheet.
     */
    public function enqueueStyle(
        string $handle,
        string $file,
        array $dependencies = [],
        ?string $version = null,
        string $media = 'all',
    ): void {
        wp_enqueue_style(
            $handle,
            $this->url($file),
            $dependencies,
            $version ?? $this->version(),
            $media,
        );
    }

    /**
     * Enqueue a script.
     */
    public function enqueueScript(
        string $handle,
        string $file,
        array $dependencies = [],
        ?string $version = null,
        array $args = ['in_footer' => true],
    ): void {
        wp_enqueue_script(
            $handle,
            $this->url($file),
            $dependencies,
            $version ?? $this->version(),
            $args,
        );
    }

    public function register(): void
    {
        add_action(
            'admin_enqueue_scripts',
            [$this, 'enqueueAdminAssets']
        );
    }

    public function enqueueAdminAssets(string $hook): void
    {
        // Only load on our plugin page
        if ($hook !== 'toplevel_page_seven-places-toolkit') {
            return;
        }

        $this->enqueueStyle(
            'spt-admin',
            'css/admin.css'
        );
    }
}
