<?php

declare(strict_types=1);

namespace SPT\Infrastructure\GitHub;

use RuntimeException;
use SPT\Core\Application;

final readonly class GitHubClient
{
    private const API = 'https://api.github.com';

    /**
     * Default cache lifetime (6 hours).
     */
    private const DEFAULT_CACHE_TTL = 6 * HOUR_IN_SECONDS;

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
        $cached = get_transient($this->cacheKey());

        if (is_array($cached)) {
            return $cached;
        }

        $release = $this->get(
            '/repos/' . $this->repository() . '/releases/latest'
        );

        $tag = ltrim(
            (string) ($release['tag_name'] ?? ''),
            'v'
        );

        if ($tag === '') {
            throw new RuntimeException(
                'GitHub release tag is missing.'
            );
        }

        set_transient(
            $this->cacheKey(),
            $release,
            $this->cacheTtl()
        );

        return $release;
    }

    /**
     * Clears the cached release information.
     */
    public function clearCache(): void
    {
        delete_transient(
            $this->cacheKey()
        );
    }

    /**
     * Returns a release by tag.
     *
     * @return array<string,mixed>
     */
    public function releaseByTag(string $tag): array
    {
        return $this->get(
            '/repos/' .
            $this->repository() .
            '/releases/tags/' .
            rawurlencode($tag)
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
     * Returns the transient cache key.
     */
    private function cacheKey(): string
    {
        return sprintf(
            '%s_github_latest_release',
            $this->app->slug()
        );
    }

    /**
     * Returns the cache lifetime.
     */
    private function cacheTtl(): int
    {
        return (int) apply_filters(
            'spt_github_cache_ttl',
            self::DEFAULT_CACHE_TTL,
            $this->app
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
            ->withHeaders([
                'Accept' => 'application/vnd.github+json',
                'User-Agent' => sprintf(
                    '%s/%s',
                    $this->app->slug(),
                    $this->app->version()
                ),
            ])
            ->get(
                self::API . $endpoint
            );

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
