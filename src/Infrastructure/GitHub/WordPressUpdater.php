<?php

declare(strict_types=1);

namespace SPT\Infrastructure\GitHub;

use SPT\Core\Application;

final readonly class WordPressUpdater
{
    public function __construct(
        private Application $app,
    ) {
    }

    /**
     * Registers the WordPress update hooks.
     */
    public function register(): void
    {
        add_filter(
            'pre_set_site_transient_update_plugins',
            [$this, 'checkForUpdates']
        );

        add_filter(
            'plugins_api',
            [$this, 'pluginInformation'],
            10,
            3
        );

        add_action(
            'upgrader_process_complete',
            [$this, 'clearGitHubCache'],
            10,
            2
        );
    }

    /**
     * Injects GitHub update information into the WordPress update transient.
     */
    public function checkForUpdates(object $transient): object
    {
        if (
            !isset($transient->checked)
            || !is_array($transient->checked)
        ) {
            return $transient;
        }

        try {
            $update = $this->app
                ->updater()
                ->check();
        } catch (\Throwable $e) {
          $this->logException($e);
          return $transient;
        }

        if (!$update->available()) {
            return $transient;
        }

        $plugin = $this->app->basename();

        $transient->response[$plugin] = (object) [
            'slug'        => $this->app->slug(),
            'plugin'      => $plugin,
            'new_version' => $update->latestVersion(),
            'url'         => $update->releaseUrl(),
            'package'     => $update->downloadUrl(),
        ];
        
        return $transient;
    }

    /**
     * Supplies information for the WordPress "View Details" modal.
     */
    public function pluginInformation(
        mixed $result,
        string $action,
        object $args,
    ): mixed {
        if (
            $action !== 'plugin_information'
            || !isset($args->slug)
            || $args->slug !== $this->app->slug()
        ) {
            return $result;
        }

        try {
            $update = $this->app
                ->updater()
                ->check();
        } catch (\Throwable $e) {
          $this->logException($e);
          return $result;
        }

        return (object) [
            'name'          => $this->app->name(),
            'slug'          => $this->app->slug(),
            'version'       => $update->latestVersion(),
            'author'        => '<a href="https://sevenplacesproductions.com">Seven Places Productions</a>',
            'homepage'      => $update->releaseUrl(),
            'download_link' => $update->downloadUrl(),
            'last_updated'  => $update->publishedAt(),
            'sections'      => [
                'description' => 'Seven Places Toolkit',
                'changelog'   => nl2br($update->releaseNotes()),
            ],
        ];
    }

    /**
     * Clears the cached GitHub release after this plugin updates.
     *
     * @param \WP_Upgrader       $upgrader
     * @param array<string,mixed> $options
     */
     private function logException(\Throwable $e): void
     {
         if (defined('WP_DEBUG') && WP_DEBUG) {
             error_log(
                 sprintf(
                     '[SPT Updater] %s: %s in %s:%d',
                     get_class($e),
                     $e->getMessage(),
                     $e->getFile(),
                     $e->getLine()
                 )
             );
         }
     }

    public function clearGitHubCache(
        \WP_Upgrader $upgrader,
        array $options,
    ): void {
        if (
            ($options['action'] ?? '') !== 'update'
            || ($options['type'] ?? '') !== 'plugin'
        ) {
            return;
        }

        $plugins = $options['plugins'] ?? [];

        if (!is_array($plugins)) {
            return;
        }

        if (!in_array($this->app->basename(), $plugins, true)) {
            return;
        }

        $this->app
            ->github()
            ->clearCache();

        update_option(
            'spt_admin_notice',
            'updated'
        );
    }
}
