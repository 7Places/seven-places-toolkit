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
    }

    /**
     * @param object $transient
     *
     * @return object
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
            $update = $this->app->updater()->check();
        } catch (\Throwable) {
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
     * @param mixed  $result
     * @param string $action
     * @param object $args
     *
     * @return mixed
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
             $update = $this->app->updater()->check();
         } catch (\Throwable) {
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
}
