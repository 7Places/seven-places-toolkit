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

    <?php

    declare(strict_types=1);

    namespace SPT\Infrastructure\GitHub;

    use RuntimeException;
    use SPT\Core\Application;

    final readonly class GitHubClient
    {
        private const API = 'https://api.github.com';

        /**
         * Cache key for the latest GitHub release.
         */
        private const LATEST_RELEASE_CACHE = 'spt_github_latest_release';

        /**
         * Cache lifetime (6 hours).
         */
        private const CACHE_TTL = 6 * HOUR_IN_SECONDS;

        public function __construct(
            private Application $app,
        ) {
        }

        /**
         * Returns the configured repository.
         */
        public function repository(): string
        {
            $repository = trim(
                $this->app
                    ->metadata()
                    ->header('GitHubRepository')
            );

            if ($repository === '') {
                throw new RuntimeException(
                    'GitHubRepository plugin header is missing.'
                );
            }

            return $repository;
        }

        /**
         * Returns the latest published release.
         *
         * @return array<string,mixed>
         */
        public function latestRelease(): array
        {
            $cached = get_transient(self::LATEST_RELEASE_CACHE);

            if (is_array($cached)) {
                return $cached;
            }

            $release = $this->get(
                '/repos/' . $this->repository() . '/releases/latest'
            );

            set_transient(
                self::LATEST_RELEASE_CACHE,
                $release,
                self::CACHE_TTL
            );

            return $release;
        }

        /**
         * Clears the cached release information.
         */
        public function clearCache(): void
        {
            delete_transient(self::LATEST_RELEASE_CACHE);
        }

        /**
         * Returns a release by tag.
         *
         * @return array<string,mixed>
         */
        public function releaseByTag(string $tag): array
        {
            return $this->get(
                '/repos/' . $this->repository() . '/releases/tags/' . rawurlencode($tag)
            );
        }

        /**
         * Returns repository information.
         *
         * @return array<string,mixed>
         */
        public function repositoryInfo(): array
        {
            return $this->get(
                '/repos/' . $this->repository()
            );
        }

        /**
         * Executes a GitHub API GET request.
         *
         * @return array<string,mixed>
         */
        private function get(string $endpoint): array
        {
            $response = $this->app
                ->http()
                ->get(self::API . $endpoint);

            if ($response->failed()) {
                throw new RuntimeException(
                    sprintf(
                        'GitHub API request failed (%d).',
                        $response->status()
                    )
                );
            }

            $json = $response->json();

            if (!is_array($json)) {
                throw new RuntimeException(
                    'GitHub returned invalid JSON.'
                );
            }

            return $json;
        }
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
         } catch (\Throwable $e) {
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
         } catch (\Throwable $e) {

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


         /**
          * Clears the cached GitHub release after this plugin updates.
          *
          * @param \WP_Upgrader $upgrader
          * @param array<string,mixed> $options
          */
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
